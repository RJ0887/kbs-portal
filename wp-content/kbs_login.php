<?php 
// Filter external inputs from form
function input_filter($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Assign data to variables
$user_email = input_filter($_POST['email']); 
$user_passw = input_filter($_POST['password']);
$passwordhashed = password_hash($user_passw,PASSWORD_DEFAULT);

include "connect.php";		// connect to db

// Check password with email
$querytest = $conn->prepare("SELECT user_passw FROM kbs_users WHERE user_email=?"); 
$querytest->bind_param("s",$user_email); 
$querytest->execute(); 
$querytest->bind_result($pass_check); 
$querytest->fetch(); 
$querytest->close();

// Get name and role of the user
$queryname = $conn->prepare("SELECT user_id, user_name,user_role FROM kbs_users WHERE user_email=?");
$queryname->bind_param("s",$user_email); 
$queryname->execute(); 
$queryname->bind_result($uid,$current_user,$user_role); 
$queryname->fetch(); 
$queryname->close();

mysqli_close($conn);

if (password_verify($user_passw, $pass_check)) {
	session_start();
	$_SESSION['login']="1"; 
	$_SESSION['uid'] = $uid;
	$_SESSION['curr_user'] = $current_user; 
	$_SESSION['role_user'] = $user_role; 
	header('Location:/wordpress/?page_id=46');
	exit;
	
} else {
	$message = "Incorrect login information.";
	echo "<script type='text/javascript'>
	alert('$message');
	window.location.href='/wordpress/?page_id=5';
	</script>";
	exit;  
}
mysqli_close($conn);
?>