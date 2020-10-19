<?php 
	$servername = "127.0.0.1"; 	// database server name 
	$username = "root"; 				// database username 
	$password = "C@readm1n#*5q1"; 			// database password 
	$dbname = "kbs"; 				// database name
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn){
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
        	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		die("Connection failed: ". mysqli_connect_error());
	} 
?>