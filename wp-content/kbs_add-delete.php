<?php 
session_start();			// start session
include "connect.php";		// connect to db

// GET options and its value
$optval = $_GET['udel'];
$opt = $_GET['opt'];
$pid = $_GET['pid'];

switch ($opt){
	case "1":
		// delete users
		$query = "DELETE FROM kbs_users WHERE user_id = '$optval'";
		break;
	case "2":
		// delete domains
		$query = "DELETE FROM kbs_domain WHERE user_domain = '$optval'";
		break;
	case "3":
		// insert new domain
		$query = "INSERT INTO kbs_domain (user_domain) VALUES ('$optval')";
		break;
	case "4":
		// delete post as admin
		$query = "DELETE FROM kbs_posts WHERE post_id = '$optval'";
		break;
	case "5":
		// delete post as pu (pend delete)
		$query = "UPDATE kbs_posts SET post_status = 'delete' WHERE post_id = '$optval'";
		break;
	case "6":
		//  remove delete status
		$query = "UPDATE kbs_posts SET post_status = '' WHERE post_id = '$optval'";
		break;
	case "7":
		// delete own comment
		$query = "DELETE FROM kbs_comment WHERE ID = '$optval'";
		break;
}
// Execute query and proceed accordingly
if (mysqli_query($conn, $query)) { 
	if ($opt == "4"){
		echo "<script>
		alert('Delete successful!');
		window.location.href='/wordpress/?page_id=67';
		</script>";
	} else if ($opt == "5") {
		echo "<script>
		alert('Delete request sent!');
		window.location.href='/wordpress/?page_id=67';
		</script>";
	} else if ($opt == "6") {
		echo "<script>
		alert('Removed from delete list!');
		window.location.href='/wordpress/?page_id=67';
		</script>";
	} else if ($opt == "7"){
		echo "<script>
		alert('Delete successful!');
		window.location.href='/wordpress/?page_id=48?pid=".$pid."&pid=".$pid."';
		</script>";
	} 
	else {
		echo "<script>
		alert('Success!');
		window.location.href='/wordpress/?page_id=57';
		</script>";
	}
	exit; 
} else {
	if ($opt == "4" || $opt == "5"){
		echo "<script>
		alert('Delete error!');
		window.location.href='/wordpress/?page_id=67';
		</script>";
	} else if ($opt == "7"){
		echo "<script>
		alert('Delete error!');
		window.location.href='/wordpress/?page_id=48?pid=".$pid."&pid=".$pid."';
		</script>";
	} 	
	else {
		echo "<script>
		alert('Error! Please try again.');
		window.location.href='/wordpress/?page_id=57';
		</script>";
	}
	exit;  
}
mysqli_close($conn);
$opt = null; $udel = null;		// reset
?>