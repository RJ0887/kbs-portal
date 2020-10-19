<?php /* 
Template Name: Uploadpage
Template Post Type: post, page, event
 */ ?>
 

<?php
session_start();	
$login = $_SESSION['login'];

if ($login == 1) {  
	
	$uploadName = $_GET['uploadName'];
	$pid = $_GET['pid'];
	include "connect.php";
		
	$pdfResult = mysqli_query($conn, "SELECT * FROM kbs_uploads WHERE post_id='$pid' AND upload_name='$uploadName'");
	if ($pdfResult->num_rows > 0) {
		while ($row = mysqli_fetch_array($pdfResult)) {
			$uploadName = $row["upload_name"];
			$uploadType = $row["upload_type"];
			$uploadData = $row["upload_data"];
		}
		echo '<iframe style="position:fixed; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" src="data:'.$certUploadType.';base64,' .$certUploadData. '"/>';
	} else {
		echo "<script>
		alert('File not found!');
		window.location.href='http://kbs.coreconsulting.asia:8008/wordpress/?page_id=46';
		</script>";
	}
	
} else {
	echo "<script>
	alert('Invalid access. Please log in!');
	window.location.href='/wordpress/?page_id=5';
	</script>";
}
?>