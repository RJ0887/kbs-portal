<?php 
session_start();	// start session

// Assign data to variables
$post_rating = $_POST['rating'];
$post_id = $_POST['pid']; 
$com_author = $_SESSION['curr_user'];
$uid = $_SESSION['uid'];
date_default_timezone_set("Asia/Kuala_Lumpur");
$com_date = date('Y-m-d H:i:s');

if(empty($post_rating) || empty($post_id)) {
	echo "<script>
	window.location.href='/wordpress/?page_id=48?pid=".$_POST['pid']."&pid=".$_POST['pid']."';
	alert('Error! Please fill up all required fields.');
	</script>";
	exit;
} else {
	include "connect.php";		// connect to db	
	// check whether user has rated before
	$querytest = $conn->prepare("SELECT post_id FROM kbs_rating WHERE post_id=? AND rating_author_id=?");
	$querytest->bind_param("ss",$post_id, $uid); 
	$querytest->execute(); 
	$querytest->bind_result($pid_check); 
	$querytest->fetch(); 
	$querytest->close();
	
	if ($pid_check != $post_id){
		// insert rating cause new
		$query = $conn->prepare("INSERT INTO kbs_rating (post_id,rating,rating_author_id,rating_date) VALUES(?,?,?,?)");
		$query->bind_param("ssss",$post_id,$post_rating,$uid,$com_date); 
		$query->execute();
		if ($query) {
			$query->close();
			$message = "Rating submitted!";
		} else {
			$query->close(); 
			$message = "Submission error! Please try again.";
		}		
	} else {
		// update existing rating and time
		$query = $conn->prepare("UPDATE kbs_rating SET rating=?, rating_date=? WHERE post_id=? AND rating_author_id=?");
		$query->bind_param("ssss",$post_rating,$com_date,$post_id,$uid); 
		$query->execute();
		if ($query) {
			$query->close();
			$message = "Rating updated!";
		} else {
			$query->close(); 
			$message = "Rating update error! Please try again.";
		}
	}
	
	// Get average rating for the post
	$rateresult = mysqli_query($conn, "SELECT * FROM kbs_rating WHERE post_id='$post_id'");
	$rowcount=mysqli_num_rows($rateresult);
	$rate = 0;
	while ($row = mysqli_fetch_array($rateresult)) { 
		$rate += $row['rating'];
	}
	$averate = ceil($rate / $rowcount);
	
	// Publish rating after getting average
	$querypost = "UPDATE kbs_posts SET post_rate = '$averate' WHERE post_id = '$post_id'";
	if (mysqli_query($conn, $querypost)) { 
		$message = "Rating submitted for the post!";
	} else {
		$message = "Rating submission error! Please try again.";
	}		
	echo "<script>window.location.href='/wordpress/?page_id=48?pid=".$_POST['pid']."&pid=".$_POST['pid']."';alert('$message');</script>";
	exit; 
	mysqli_close($conn);
}
?>