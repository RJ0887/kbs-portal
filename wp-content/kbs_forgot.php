<?php 
// Filter external inputs from form
function input_filter($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Assign data to Variables
$user_email = input_filter($_POST['email']); 

include "connect.php";		// connect to db

// Check password with email (username)
$querytest = $conn->prepare("SELECT user_passw FROM kbs_users WHERE user_email=?"); 
$querytest->bind_param("s",$user_email); 
$querytest->execute(); 
$querytest->bind_result($pass_check); 
$querytest->fetch(); 
$querytest->close();
mysqli_close($conn);

$body = "Please reset your password.";

if ($pass_check) {
	echo "<script type='text/javascript'>
	alert('Password is $pass_check');
	window.location='/wordpress/?page_id=5';
	</script>";
	exit;  
} else {
	echo "<script type='text/javascript'>
	alert('Email not found!');
	window.location='/wordpress/?page_id=5';
	</script>";
	exit;  
}

mysqli_close($conn);
?>