<?php 
require 'PHPMailerAutoload.php';

// Filter external inputs from form
function input_filter($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$empPassword = rand(100000,999999);
$newpasswordhashed = password_hash($empPassword,PASSWORD_DEFAULT);

// Assign data to Variables
$user_email = input_filter($_POST['email']); 

include "connect.php";		// connect to db
include "connect2.php";		// connect to db

$queryCheck = mysqli_query($conn, "SELECT * FROM kbs_users WHERE user_email='$user_email'");
if(mysqli_num_rows($queryCheck) > 0){
	// means email exist then proceed to update password
	// check whether email account is employee
	$query2 = mysqli_query($conn2, "SELECT * FROM hr_emp_account WHERE empEmail='$user_email'");
	if(mysqli_num_rows($query2) > 0){
		$query3 = $conn2->prepare("UPDATE hr_emp_account SET empPassword=? WHERE empEmail='$user_email'");
		$query3->bind_param("s",$newpasswordhashed); 
		$query3->execute();
		$query3->close();
	}
			
	$query = $conn->prepare("UPDATE kbs_users SET user_passw=? WHERE user_email='$user_email'");
	$query->bind_param("s",$newpasswordhashed); 
	$query->execute();
	if (($query->affected_rows)>0) { 
	
		// send email with the changed password
		$mail = new PHPMailer();
		$body = "A new password has been assigned to your account and your new login details are as follow:<br>Email: ".$user_email."<br>Password: ".$empPassword."<br><br>Please login and change the above password as to your preference <a href='http://kbs.coreconsulting.asia:8008/wordpress'>here</a>.<br><br>Have a good day!";

		//$mail->IsSMTP();
		//$mail->SMTPDebug = 3;
		$mail->IsSMTP();                                      	// Set mailer to use SMTP
		$mail->Host = '192.168.88.10';  						// Specify main and backup server
		$mail->Port = '25';
		$mail->SMTPAuth = false;                               	// Enable SMTP authentication
		$mail->SetFrom('support@coreconsulting.asia', 'Support');
		$mail->Subject = "Core Consulting Account Password Reset";
		$mail->MsgHTML($body);
		$mail->AddAddress($user_email, "");

		if($mail->Send()) {
			echo "<script>
			window.location.href='/wordpress/?page_id=5';
			alert('New password has been set for your account! Please check your email for more details.');
			</script>"; 
		} else {
			echo "<script>
			window.location.href='/wordpress/?page_id=5';
			alert('New password has been set for your account!. Email on details failed to send!');
			</script>"; 
		}		

	} else {
		echo "<script>
		window.location.href='/wordpress/?page_id=5';
		alert('Password reset error! Please try again.');
		</script>"; 
	}	
	$query->close();
	
} else {
	echo "<script type='text/javascript'>
	alert('Email not found!');
	window.location='/wordpress/?page_id=5';
	</script>";
	exit;  
}

mysqli_close($conn);
mysqli_close($conn2);
?>