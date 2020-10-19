<?php /* 
Template Name: ManageProfilepage
Template Post Type: post, page, event
 */ ?>
 
<?php
// Start the session
session_start();
define( 'WP_USE_THEMES', false );
get_header(); 

// If not log in, go to login page
if(!(isset($_SESSION['login']))){
    echo "<script type='text/javascript'>
	alert('Insufficient privileges for access. Please log in.');
	window.location='/wordpress/?page_id=5';
	</script>";
	exit; 
} else {
	include "connect.php";
	$user_role = $_SESSION['role_user'];
	$author = $_SESSION['curr_user'];
	$uid = $_SESSION['uid'];
?>

<style>
#marginoverall {
	margin: 1em 5em 0em 5em;
}

#leftcolumn {
	float:left;
	width:50%;
}

#rightcolumn {
	float:right;
	width:50%;
	padding-left: 3em;
}

/* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 10px 10px;
    transition: 0.3s;
    font-size: 14px;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
	overflow: auto;
	height: 500px;
}

.tacontent {
    padding: 6px 12px;
    border: 1px solid #ccc;
	overflow: auto;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

tr {
	transition: background-color .2s;
}

tr:hover{
	background-color:#383838;
}

.center {
	text-align: center;
}

ul {
	text-transform: capitalize;
}

a {
	cursor:pointer;
}


.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 3; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
   
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.8); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
	margin: 15% auto; /* 15% from the top and centered */
    padding: 50px;
    border: 1px solid #888;
    width: 50%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {	
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: gray;
    text-decoration: none;
    cursor: pointer;
}
</style>
<div id = "marginoverall">
	<h6 style="text-transform: capitalize;">My Profile</h6>
	
		<?php
		$userresult = mysqli_query($conn, "SELECT * FROM kbs_users WHERE user_id='$uid'");
		// viewTableUser($userresult);
		$row = mysqli_fetch_assoc($userresult);
		echo "<h5>Name</h5>".$row['user_name']."<br><br>";
		echo "<h5>Role</h5>".$row['user_role']."<br><br>";
		echo "<h5>Email</h5>".$row['user_email']."<br>";
		?>
	<br>
	<button id="myBtn">Change Password</button>
	<div id="myModal" class="modal" style="color:white;">
		<!-- Modal content -->
		<div class="modal-content">
			<span class="close">&times;</span>
			<form style="width:50%;" method="post" action="/wordpress/wp-content/kbs_changepw.php">
				Enter email:<input id="email" name="email" type="email" required/><br>
				Enter current password:<input id="curpassword" name="curpassword" type="password" required/><br>
				Enter new password:<input id="newpassword" name="newpassword" type="password" required/><br>
				Retype new password:<input id="rnewpassword" name="rnewpassword" type="password" required/><br>
				<input type="submit" value="Submit" />
			</form>
		</div>

	</div>
</div>

<script type="text/javascript">
	
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php get_footer();
}
?>