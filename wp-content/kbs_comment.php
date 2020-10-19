<?php 
session_start();	// start session

// Assign data to variables
$comment = $_POST['comment'];
$post_id = $_POST['pid']; 
$com_author = $_SESSION['curr_user'];
$uid = $_SESSION['uid'];
date_default_timezone_set("Asia/Kuala_Lumpur");
$com_date = date('Y-m-d H:i:s');

if(empty($comment) || empty($post_id) || empty($com_author)) {
	echo "<script>
	window.location.href='/wordpress/?page_id=48?pid=".$post_id."&pid=".$post_id."';
	alert('Error! Please fill up all required fields.');
	</script>";
	exit;
} else {
	include "connect.php";		// connect to db
	// Post comment content
	$query = $conn->prepare("INSERT INTO kbs_comment (post_id,comment,comment_author_id,comment_date) VALUES(?,?,?,?)");
	$query->bind_param("ssss",$post_id,$comment,$uid,$com_date); 
	$query->execute();
	if (($query->affected_rows)>0) {
		$query->close();
		$message = "Comment published!";
	} else {
		$query->close(); 
		$message = "Publish error! Please try again.";
	}
	echo "<script>window.location.href='/wordpress/?page_id=48?pid=".$post_id."&pid=".$post_id."';alert('$message');</script>";
	exit; 
	mysqli_close($conn);
}
?>