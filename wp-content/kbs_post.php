<?php 
session_start();

$pid = $_GET['pid'];

// Assign data to Variables
$post_title = $_POST['title'];
$post_type = $_POST['type']; 
$post_critlvl = $_POST['critlvl'];
$post_content = $_POST['content'];
$post_author = $_SESSION['curr_user'];
$uid = $_SESSION['uid'];
date_default_timezone_set("Asia/Kuala_Lumpur");
$post_date = date('Y-m-d H:i:s');

if ($post_type == "product"){
	$post_prod_ver = $_POST['version'];
	$post_prod_environ = $_POST['environment'];
	$post_prod_component = $_POST['component'];
	$post_category = $_POST['category'];
} else if ($post_type == "general"){
	$post_category = $_POST['category2'];
}

// if there's product info, concatenate with post
if ((!empty($post_prod_ver)) || (!empty($post_prod_environ)) || (!empty($post_prod_component))){
	if (empty($post_prod_ver)) {$post_prod_ver = "-";}
	if (empty($post_prod_environ)) {$post_prod_environ = "-";}
	if (empty($post_prod_component)) {$post_prod_component = "-";}
	$post_content = "Product version: ".$post_prod_ver."<br>Product environment: ".$post_prod_environ."<br>Component: ".$post_prod_component."<br><br>".$post_content;
}

if(empty($post_title) || empty($post_type) || empty($post_content)) { 
	echo "<script>
	window.location.href='/websiteTestEJ/?page_id=93';
	alert('Error! Please fill up all required fields.');
	</script>";
	exit;
} else {
	include "connect.php";		// connect to db
	
	// Post new category if any
	if ($post_type == "product"){
		$queryprod = $conn->prepare("SELECT category FROM kbs_product_category WHERE category=?");
		$queryprod->bind_param("s",$post_category); 
		$queryprod->execute(); 
		$queryprod->bind_result($cat_check); 
		$queryprod->fetch(); 
		$queryprod->close();
		if ($cat_check != $post_category){
			$query_cat = $conn->prepare("INSERT INTO kbs_product_category (category) VALUES (?)");
			$query_cat->bind_param("s",$post_category);
			$query_cat->execute();
		}
	} else if ($post_type == "general"){
		$querygen = $conn->prepare("SELECT category FROM kbs_general_category WHERE category=?");
		$querygen->bind_param("s",$post_category); 
		$querygen->execute(); 
		$querygen->bind_result($cat_check); 
		$querygen->fetch(); 
		$querygen->close();
		if ($cat_check != $post_category){
			$query_cat = $conn->prepare("INSERT INTO kbs_general_category (category) VALUES (?)");
			$query_cat->bind_param("s",$post_category);
			$query_cat->execute();
		}
	}
	// Post main content
	$post_content_edit = nl2br($post_content);
	if ($pid == null){
		// create
		$query = $conn->prepare("INSERT INTO kbs_posts (post_author_id,post_date,post_title,post_type,post_content,post_category,post_critlvl,post_modified) VALUES(?,?,?,?,?,?,?,?)");
		$query->bind_param("ssssssss",$uid,$post_date,$post_title,$post_type,$post_content_edit,$post_category,$post_critlvl,$post_date);
	} else if ($pid != null){
		// edit
		$query = $conn->prepare("UPDATE kbs_posts SET post_author_id=?,post_modified=?,post_type=?,post_content=?,post_category=?,post_critlvl=? WHERE post_id=?");
		$query->bind_param("sssssss",$uid,$post_date,$post_type,$post_content_edit,$post_category,$post_critlvl,$pid);
	}
	$query->execute();
	
	////////////////////////////////////// POST IMAGE /////////////////////////////////////////////////////
	// Post images if any// Get last post id from kbs_posts
	$querypid = $conn->prepare("SELECT post_id FROM kbs_posts ORDER BY post_id DESC LIMIT 1");
	$querypid->execute(); 
	$querypid->bind_result($pid_new); 
	$querypid->fetch(); 
	$querypid->close();
	
	
	if($_FILES["files"]["error"] > 0){
		$errors= array();
		//File uploaded
		foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
			$file_name = $_FILES['files']['name'][$key];
			$file_size =$_FILES['files']['size'][$key];
			$file_tmp =$_FILES['files']['tmp_name'][$key];
			$file_type=$_FILES['files']['type'][$key];
			
			if ($file_size != null){
				// Image submitted by form. Open it for reading (mode "r")
				$fp = fopen($file_tmp, 'r');
				// Read from the file pointer using the size of the file (in bytes) as the length.
				$content = fread($fp, filesize($file_tmp));
				rewind($fp);
				fclose($fp);
				$content = base64_encode($content);
				$file_name= mysqli_real_escape_string($conn, $file_name);
				
				// file size limit:
				//if($file_size > 2097152){
				//	$errors[]='File size must be less than 2 MB';
				//	exit;
				//}
				
				if ($pid == null){
					// new post plus one because in kbs_posts haven't increment
					$query_up = $conn->prepare("INSERT into kbs_uploads (post_id,upload_name,upload_type,upload_size,upload_data) VALUES(?,?,?,?,?)");
					$query_up->bind_param("sssss",$pid_new,$file_name,$file_type,$file_size,$content);	
					$query_up->execute();
					$query_up->close();					
				} else if ($pid != null){
					// old post for edit
					$querytest = $conn->prepare("SELECT post_id FROM kbs_uploads WHERE post_id=?");
					$querytest->bind_param("s",$pid); 
					$querytest->execute(); 
					$querytest->bind_result($pid_check); 
					$querytest->fetch(); 
					$querytest->close();
				
					if (($pid == $pid_check) && ($key == '0')){
						// delete all other medias with same pid
						$query = "DELETE FROM kbs_uploads WHERE post_id = '$pid'";
						mysqli_query($conn, $query);					
					}
					$query_up = $conn->prepare("INSERT into kbs_uploads (post_id,upload_name,upload_type,upload_size,upload_data) VALUES(?,?,?,?,?)");
					$query_up->bind_param("sssss",$pid,$file_name,$file_type,$file_size,$content);
					$query_up->execute();
					$query_up->close();
				}
			}	
		}
	}
		////////////////////////////////////// POST IMAGE /////////////////////////////////////////////////////
	if ($query) { 
		echo "<script>window.location.href='/wordpress/?page_id=46';alert('Content published!');</script>";
	} else {
		echo "<script>window.location.href='/wordpress/?page_id=50';alert('Publish error! Please try again.');</script>";
	}
	$query->close(); 
	exit;
}
mysqli_close($conn);
?>