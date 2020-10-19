<?php /* Template Name: Logout */ ?>
<?php
	// Start the session
	session_start(); 
	session_destroy(); 
	echo "<script type='text/javascript'>
	alert('Successfully logged out!');
	window.location='/wordpress/';
	</script>";
?>
<?php get_footer();
