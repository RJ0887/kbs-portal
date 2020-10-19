<?php /* 
Template Name: ManageUserpage
Template Post Type: post, page, event
 */ ?>
 
<?php
// Start the session
session_start();
define( 'WP_USE_THEMES', false );
get_header(); 

function getAuthorName($aid) {
	include "connect.php";
	$authorResult = mysqli_query($conn, "SELECT * FROM kbs_users WHERE user_id='$aid'");
	while ($row = mysqli_fetch_array($authorResult)) { 
		$authorName = $row['user_name'];
	}
	return $authorName;	
}

// If not log in, go to login page
if(!(isset($_SESSION['login']))){
    echo "<script type='text/javascript'>
	alert('Insufficient privileges for access. Please log in.');
	window.location='/wordpress/?page_id=5';
	</script>";
	exit; 
} else if (($_SESSION['role_user'] == "admin")){
	include "connect.php";
	$user_role = $_SESSION['role_user'];
	$author = $_SESSION['curr_user'];
?>

<html lang="en-us">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
* {
    box-sizing: border-box;
}
.row::after {
    content: "";
    clear: both;
    display: table;
}
[class*="col-"] {
    float: left;
	padding: 1.5em;
}

/* For mobile phones: */
[class*="col-"] {
    width: 100%;
}

@media only screen and (min-width: 320px) {
    /* For tablets: */
    .col-m-1 {width: 8.33%;}
    .col-m-2 {width: 16.66%;}
    .col-m-3 {width: 25%;}
    .col-m-4 {width: 33.33%;}
    .col-m-5 {width: 41.66%;}
    .col-m-6 {width: 50%;}
    .col-m-7 {width: 58.33%;}
    .col-m-8 {width: 66.66%;}
    .col-m-9 {width: 75%;}
    .col-m-10 {width: 83.33%;}
    .col-m-11 {width: 91.66%;}
    .col-m-12 {width: 100%;}
}

@media only screen and (min-width: 600px) {
    /* For tablets: */
    .col-m-1 {width: 8.33%;}
    .col-m-2 {width: 16.66%;}
    .col-m-3 {width: 25%;}
    .col-m-4 {width: 33.33%;}
    .col-m-5 {width: 41.66%;}
    .col-m-6 {width: 50%;}
    .col-m-7 {width: 58.33%;}
    .col-m-8 {width: 66.66%;}
    .col-m-9 {width: 75%;}
    .col-m-10 {width: 83.33%;}
    .col-m-11 {width: 91.66%;}
    .col-m-12 {width: 100%;}
}
@media only screen and (min-width: 961px) {
    /* For desktop: */
    .col-1 {width: 8.33%;}
    .col-2 {width: 16.66%;}
    .col-3 {width: 25%;}
    .col-4 {width: 33.33%;}
    .col-5 {width: 41.66%;}
    .col-6 {width: 50%;}
    .col-7 {width: 58.33%;}
    .col-8 {width: 66.66%;}
    .col-9 {width: 75%;}
    .col-10 {width: 83.33%;}
    .col-11 {width: 91.66%;}
    .col-12 {width: 100%;}
}
/************************** OVERALL LAYOUT *********************************/
#marginoverall {
	margin: 0em 0em 0em 2em;
}
/************************** DIV TAB CONTAINERS *****************************/
/* Style the tab */
div.tab {
    overflow: hidden;
    background-color: white;	
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
	color: black;
	font-size: 14px;
	font-weight: bold;
    float: left;
    cursor: pointer;
    padding: 10px 20px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
	color: #0229bf;
}

/* Create an active/current tablink class */
div.tab button.active {
	//box-shadow: inset 0 -3px 0 0 #3F51B5;
	color: white;
	background-color: #3F51B5;
}

/************************** LATEST UPDATES *****************************/
/* Style the tab content - latest update */
.tabcontent {
	overflow: auto;
	height: 600px;
}

.tabcontent th {
	color: #3F51B5;
	padding: 10px;
	border-bottom: 5px solid #3F51B5;
	padding-left: 10px;
}

.tabcontent td {
	padding-left: 10px;
	padding-right: 20px;
}

/************************** VIEW TABLE *****************************/
table {
	margin-top: 10px;
	margin-bottom: 0px;
	width: 100%;
    border-collapse: collapse;
	background-color: white;
}

// th:nth-child(1){
     // width:70%;
// }

// th:nth-child(2), th:nth-child(3){
     // width:12%;
// }

table td > p {
	font-size:1em;
}

table, th, td {
	border-left: none;border-right: none;border-top: none;border-bottom: none;
}

tr:not(:first-child):hover{
	background-color:#ddd;
	transition: background-color .2s;
}

tr:nth-child(even){
	background-color: #f2f2f2;
}

.tab {
	border-bottom: 1px solid #ddd;
}
</style>
</head>

<?php
function viewTableUser($queryresult){
	echo "<div style='overflow-y:auto'>"; 
	echo "<a id=link>";
	echo "<table id='DataTable', border='1', class='widget widget_categories'>
	<tr> 
	  <th>Name</th>
	  <th>Role</th>
	  <th>Email</th>
	  <th>Action</th>
	</tr>";
	while ($row = mysqli_fetch_array($queryresult)) {
		echo "<tr>";
		echo "<td>".$row['user_name']."</td>";
		echo "<td>".$row['user_role']."</td>";
		echo "<td>".$row['user_email']."</td>";
		echo "<td><i>[<a href='/wordpress/wp-content/kbs_add-delete.php?uname=".$row['user_id']."&udel=".$row['user_id']."&opt=1'
		class='confirmation'>Delete</a>]</i></td>";
		echo "</tr>"; 
	}
	echo "</table>";
	echo "</a>";
	echo "</div>"; //Fetch array and plot in table
}

function viewTableDomain($queryresult){
	echo "<div style='overflow-y:auto'>"; 
	echo "<a id=link>";
	echo "<table id='DataTable', border='1', class='widget widget_categories'>
	<tr> 
	  <th>Domain</th>
	  <th>Action</th>
	</tr>";
	echo "<tr>
			<td colspan='2'><i>
			[<a onclick='addLink();'>Add New Domain</a>]</i></td>
		 </tr>";
	while ($row = mysqli_fetch_array($queryresult)) {
		echo "<tr>";
		echo "<td>".$row['user_domain']."</td>";
		echo "<td><i>[<a href='/wordpress/wp-content/kbs_add-delete.php?udom=".$row['user_domain']."&udel=".$row['user_domain']."&opt=2'
		class='confirmation'>Delete</a>]</i></td>";
		echo "</tr>"; 
	}
	echo "</table>";
	echo "</a>";
	echo "</div>"; //Fetch array and plot in table
}
?>

<body>
<div id = "marginoverall">
<div class="row">
<div class="col-9 col-m-12">
	<div id = "leftcolumn">
		<div class="tab">
			<button class="tablinks" onclick="openTab(event, 'Product')" id="defaultOpen">Registered Users</button>
			<button class="tablinks" onclick="openTab(event, 'General')">Registered Domains</button>
		</div>
		
		<div id="Product" class="tabcontent">
			<?php
			$userresult = mysqli_query($conn, "SELECT * FROM kbs_users ORDER BY user_id ASC");
			viewTableUser($userresult);
			?>
		</div>
	
		<div id="General" class="tabcontent">
			<?php
			$domainresult = mysqli_query($conn, "SELECT * FROM kbs_domain ORDER BY user_domain ASC");
			viewTableDomain($domainresult);
			?>
		</div>
	</div>
</div>
<?php include "page-sidebar.php";  ?>
</div>
</div>
</body>
</html>

<script type="text/javascript">
function openTab(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();

function setLink(elRow) {
	var elLink = document.getElementById('link');
	var id = <?php echo $post_id; ?>
	elLink.href = "?page_id=329?id="+ "<?php echo $row['post_id']; ?>";
}

    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
	
    function addLink(){
		var dom = prompt("Please enter new domain:");
		if (dom === null){
			return;
		} else if (dom!="") {
			window.location.href='/wordpress/wp-content/kbs_add-delete.php?udom=dom&udel=' + dom + '&opt=3';
		} else {
			alert('Empty field!');
		}
	}
	
</script>

<?php get_footer();
}
else {
	echo "<script type='text/javascript'>
	alert('Insufficient privileges for access.');
	window.location='/wordpress/?page_id=46';
	</script>";
	exit;
} 
?>