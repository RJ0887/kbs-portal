<?php 

// Filter external inputs from form
function input_filter($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Assign data to variables
$user_name = input_filter($_POST['name']);
$user_email = input_filter($_POST['email']); 
$user_passw = input_filter($_POST['password']);
$user_cpassw = input_filter($_POST['cpassword']);

if(empty($user_name) || empty($user_email) || empty($user_passw) || empty($user_cpassw)) { 
	// Check if any entries are empty
	echo "<script>
	window.location.href='/wordpress/?page_id=61';
	alert('Error! Please fill up all required fields.');
	</script>";
	exit;
} else {
	if ($user_passw!=$user_cpassw){
		echo "<script>
		window.location.href='/wordpress/?page_id=61';
		alert('Error! Password mismatch!');
		</script>";
		exit;
	
	} else {
		include "connect.php";		// connect to db
		// Check email domain to assign user role
		list($front,$back) = explode("@",$user_email);
		$user_domain = explode(".",$back);
		$querydom = $conn->prepare("SELECT user_domain FROM kbs_domain WHERE user_domain=?"); 
		$querydom->bind_param("s",$user_domain[0]); 
		$querydom->execute(); 
		$querydom->bind_result($domain_check); 
		$querydom->fetch(); 
		$querydom->close();
		
		if ($user_domain[0]==$domain_check) {
			if ($user_domain[0]==="coreconsulting"){
				$user_role="power user";
			} else {
				$user_role="user";
			}
		}
		else {
			$user_role="guest";
		}	
	
		$passwordhashed = password_hash($user_passw,PASSWORD_DEFAULT);
		$query = $conn->prepare("INSERT INTO kbs_users (user_name,user_email,user_passw,user_role) VALUES(?,?,?,?)");
		$query->bind_param("ssss",$user_name,$user_email,$passwordhashed,$user_role); 
		$query->execute();
		if (($query->affected_rows)>0) { 
			echo "<script>
			window.location.href='/wordpress/?page_id=5';
			alert('Thank you for registering. You can now login.');
			</script>"; 
		} else {
			echo "<script>
			window.location.href='/wordpress/?page_id=61';
			alert('Registration error! Please try again.');
			</script>"; 
		}
		$query->close();
		exit;
		mysqli_close($conn);
	}
}
?>