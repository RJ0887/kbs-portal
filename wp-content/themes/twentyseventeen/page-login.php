<?php /* 
Template Name: MainLoginpage
Template Post Type: post, page, event
 */ ?>
 
<?php
define( 'WP_USE_THEMES', false );
get_header(); 

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
<div id = "marginoverall" style="width:50%;">
	<form style="width:100%;" action="/wordpress/wp-content/kbs_login.php" method="post">
		<input id="email" name="email" required="" type="email" placeholder="Email" /><br>
		<input id="password" name="password" required="" type="password" placeholder="Password" /><br>
		<input type="submit" value="Login" />
	</form>
	<a id='myBtn' style="font-size: 10px;">Forgot password?</a>
</div>
<br>
<div id="myModal" class="modal" style="color:white;">
	<!-- Modal content -->
	<div class="modal-content">
		<span class="close">&times;</span>
		<form style="width:50%;" method="post" action="/wordpress/wp-content/kbs_recover.php">
			Enter email:<input id="email" name="email" type="email" required/><br>
			<input type="submit" value="Submit" />
		</form>
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
?>