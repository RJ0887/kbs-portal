<?php 
	$servername = "localhost"; 	// database server name 
	$username = "root"; 				// database username 
	$password = "C@readm1n#*5q1"; 			// database password 
	$dbname = "hrcore"; 		// database name
	$conn2 = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn){
		die("Connection failed: ". mysqli_connect_error()); 
	} 
?>