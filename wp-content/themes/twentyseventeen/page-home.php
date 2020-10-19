<?php /* 
Template Name: Homepage
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

// Define critlvl to be shown in tech alerts:
$alertlvl = "3";	
include "connect.php";
//include "page-sidebar.php";
function getAuthorName($aid) {
	include "connect.php";
	$authorResult = mysqli_query($conn, "SELECT * FROM kbs_users WHERE user_id='$aid'");
	while ($row = mysqli_fetch_array($authorResult)) { 
		$authorName = $row['user_name'];
	}
	return $authorName;	
}
?>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<html lang="en-us">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
    .col-m-1 {width: 8.33% !important;}
    .col-m-2 {width: 16.66% !important;}
    .col-m-3 {width: 25% !important;}
    .col-m-4 {width: 33.33% !important;}
    .col-m-5 {width: 41.66% !important;}
    .col-m-6 {width: 50% !important;}
    .col-m-7 {width: 58.33% !important;}
    .col-m-8 {width: 66.66% !important;}
    .col-m-9 {width: 75% !important;}
    .col-m-10 {width: 83.33% !important;}
    .col-m-11 {width: 91.66% !important;}
    .col-m-12 {width: 100% !important;}
}

@media only screen and (min-width: 600px) {
    /* For tablets: */
    .col-m-1 {width: 8.33% !important;}
    .col-m-2 {width: 16.66% !important;}
    .col-m-3 {width: 25% !important;}
    .col-m-4 {width: 33.33% !important;}
    .col-m-5 {width: 41.66% !important;}
    .col-m-6 {width: 50% !important;}
    .col-m-7 {width: 58.33% !important;}
    .col-m-8 {width: 66.66% !important;}
    .col-m-9 {width: 75% !important;}
    .col-m-10 {width: 83.33% !important;}
    .col-m-11 {width: 91.66% !important;}
    .col-m-12 {width: 100% !important;}
}
@media only screen and (min-width: 961px) {
    /* For desktop: */
    .col-1 {width: 8.33% !important;}
    .col-2 {width: 16.66% !important;}
    .col-3 {width: 25% !important;}
    .col-4 {width: 33.33% !important;}
    .col-5 {width: 41.66% !important;}
    .col-6 {width: 50% !important;}
    .col-7 {width: 58.33% !important;}
    .col-8 {width: 66.66% !important;}
    .col-9 {width: 75% !important;}
    .col-10 {width: 83.33% !important;}
    .col-11 {width: 91.66% !important;}
    .col-12 {width: 100% !important;}
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
	height: 450px;
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

.LUTitle {
	color: white;
	font-weight: bold;
	font-size: 1.2em;
}

/************************** TECH ALERTS *****************************/
/* Style the content - tech alert */

.tacontent th {
	color: #F44236;
	padding: 10px;
	border-bottom: 5px solid #F44236;
	padding-left: 10px;
}

.tacontent td {
	padding-left: 10px;
	padding-right: 20px;
}

.TATitle {
	color: white;
	font-weight: bold;
	font-size: 1.2em;
}
/************************** VIEW TABLE *****************************/
table {
	margin-top: 10px;
	margin-bottom: 0px;
	width: 100%;
    border-collapse: collapse;
	background-color: white;
}

th:nth-child(1){
     width:70%;
}

th:nth-child(2), th:nth-child(3){
     width:12%;
}

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
</style>
</head>

<?php
function viewTable($queryresult){
	echo "<div style='overflow-y:auto'>"; 
	echo "<a id=link>";
	echo "<table id='DataTable', border='1', class='widget widget_categories'>
	<tr> 
	  <th>Title</th>
	  <th>Date</th>
	  <th>Category</th>
	  <th>Author</th>
	</tr>";
	while ($row = mysqli_fetch_array($queryresult)) {
		echo "<tr>"; 
		echo "<td>"."<b>"."<a href='/wordpress/?page_id=48?pid=".$row['post_id']."&pid=".$row['post_id']."'>".$row['post_title']."</a>".
		"</b><br><p>".implode(' ', array_slice(explode(' ', $row['post_content']), 0, 30))." ..."."</p></td>";
		$date = new DateTime($row['post_modified']);
		echo "<td><p>".date_format($date, 'g:i:sa jS F Y')."</p></td>";
		echo "<td><p>".$row['post_category']."</p></td>";
		echo "<td><p>".getAuthorName($row['post_author_id'])."</p></td>";
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
		<div class="w3-card-4 w3-section">
			<div class="w3-container w3-red">
				<h6 class="TATitle">Technical Alerts</h6>
			</div>
			<div id="ta" class="tacontent">
			<?php
			$techalertresult = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE post_critlvl='3' ORDER BY post_id DESC LIMIT 0,3");
			viewTable($techalertresult);
			?>
		</div>

		</div><br>
		
		<div class="w3-card-4 w3-section">
			<div class="w3-container w3-indigo">
				<h6 class="LUTitle">Latest Updates</h6>
			</div>

		<div class="tab">
			<button class="tablinks" onclick="openTab(event, 'Product')" id="defaultOpen">Product</button>
			<button class="tablinks" onclick="openTab(event, 'General')">General</button>
		</div>

		<div id="Product" class="tabcontent">
			<?php
			$updatesresult_prod = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE post_type='product' ORDER BY post_id DESC");
			viewTable($updatesresult_prod);
			?>
		</div>

		<div id="General" class="tabcontent">
			<?php
			$updatesresult_gen = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE post_type='general' ORDER BY post_id DESC");
			viewTable($updatesresult_gen);
			?>
		</div>
	</div>
</div>
</div>
<?php include "page-sidebar.php";  ?>
</div>
</div>
</body>
</html>


<script>
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
</script>

<?php get_footer();
}?>