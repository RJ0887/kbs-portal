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
$user_curpassw = input_filter($_POST['curpassword']);
$user_newpassw = input_filter($_POST['newpassword']);
$user_rnewpassw = input_filter($_POST['rnewpassword']);

if(empty($user_curpassw) || empty($user_newpassw) || empty($user_rnewpassw)) { 
	// Check if any entries are empty
	echo "<script>
	window.location.href='/wordpress/?page_id=105';
	alert('Error! Please fill up all required fields.');
	</script>";
	exit;
} else {
	if ($user_newpassw!=$user_rnewpassw){
		echo "<script>
		window.location.href='/wordpress/?page_id=105';
		alert('Error! Password mismatch.');
		</script>";
		exit;
	
	} else {
		include "connect.php";		// connect to db	(kbs)
		include "connect2.php";		// connect to db	(hrportal)
		$passwordhashed = password_hash($user_curpassw,PASSWORD_DEFAULT);
		$newpasswordhashed = password_hash($user_rnewpassw,PASSWORD_DEFAULT);
		
		$querycheck = $conn->prepare("SELECT user_passw FROM kbs_users WHERE user_email=?"); 
		$querycheck->bind_param("s",$user_email); 
		$querycheck->execute(); 
		$querycheck->bind_result($pass_check); 
		$querycheck->fetch(); 
		$querycheck->close();
		
		if (password_verify($user_curpassw, $pass_check)) {
			$query2 = mysqli_query($conn2, "SELECT * FROM hr_emp_account WHERE empEmail='$user_email'");
			if(mysqli_num_rows($query2) > 0){
				$query3 = $conn2->prepare("UPDATE hr_emp_account SET empPassword=? WHERE empEmail='$user_email'");
				$query3->bind_param("s",$newpasswordhashed); 
				$query3->execute();
				$query3->close();
			}
			
			$query = $conn->prepare("UPDATE kbs_users SET user_passw=?");
			$query->bind_param("s",$newpasswordhashed); 
			$query->execute();
			if (($query->affected_rows)>0) { 
				echo "<script>
				window.location.href='/wordpress/?page_id=105';
				alert('Password changed success!');
				</script>"; 
			} else {
				echo "<script>
				window.location.href='/wordpress/?page_id=105';
				alert('Password change error! Please try again.');
				</script>"; 
			}	
			$query->close();
		}
		else {
			echo "<script>
			window.location.href='/wordpress/?page_id=105';
			alert('Invalid password! Please try again or contact administrator.');
			</script>"; 
		}			
		exit;
		mysqli_close($conn);
	}
}
?>