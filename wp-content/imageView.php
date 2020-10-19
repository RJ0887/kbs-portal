<?php
// Start the session
session_start();

include "connect.php";

// if (isset($_GET['id'])){

	
	$imageresult = mysqli_query($conn, "SELECT * FROM kbs_uploads WHERE upload_id ='15'");
	while ($row = mysqli_fetch_assoc($imageresult)) { 
		$imageData = $row['upload_data'];
		$imageType = $row['upload_type'];
		
		header("content-type: image/jpeg");
		//echo $imageData;
		echo base64_decode($imageData);
	}
	
// } else {
	// echo "Error";
// }


 
?>