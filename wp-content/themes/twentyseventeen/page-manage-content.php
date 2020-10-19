<?php /* 
Template Name: ManageContentpage
Template Post Type: post, page, event
 */ ?>
 
<?php
// Start the session
session_start();
$user_role = $_SESSION['role_user'];
$author = $_SESSION['curr_user'];
$uid = $_SESSION['uid'];
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
} else if (($_SESSION['role_user'] == "admin") || ($_SESSION['role_user'] == "power user")){
	include "connect.php";
	
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

.tab {
	border-bottom: 1px solid #ddd;
}
</style>
</head>

<?php
function viewTable($queryresult, $status){
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
		echo "<td>"."<b>"."<a href='/wordpress/?page_id=48?pid=".$row['post_id']."&pid=".$row['post_id']."'>".$row['post_title']."</a></b>";
		if ($status == "1"){
			// edit
			echo "&nbsp&nbsp&nbsp<i>[<a href='/wordpress/?page_id=59?pid=".$row['post_id']."&pid=".$row['post_id']."'>Edit</a> / ";
			if ($_SESSION['role_user'] === "admin"){
				// straight delete (opt = 4)
				echo "<a href='/wordpress/wp-content/kbs_add-delete.php?pid=".$row['post_id']."&udel=".$row['post_id']."&opt=4' class='confirmation'>Delete</a>]</i>";
			} else if ($_SESSION['role_user'] === "power user"){
				// pend delete (opt = 5)
				echo "<a href='/wordpress/wp-content/kbs_add-delete.php?pid=".$row['post_id']."&udel=".$row['post_id']."&opt=5' class='confirmation'>Delete</a>]</i>";
			}
		} else if ($status == "2"){
			// delete
			echo "&nbsp&nbsp&nbsp<i>[<a href='/wordpress/wp-content/kbs_add-delete.php?pid=".$row['post_id']."&udel=".$row['post_id']."&opt=4' class='confirmation'>Delete</a> / ";
			echo "<a href='/wordpress/wp-content/kbs_add-delete.php?pid=".$row['post_id']."&udel=".$row['post_id']."&opt=6' class='confirmation2'>Remove from List</a>]</i>";
		}
		echo "<p>".implode(' ', array_slice(explode(' ', $row['post_content']), 0, 30))." ..."."</p></td>";
		$date = new DateTime($row['post_modified']);
		echo "<td>".date_format($date, 'g:i:sa jS F Y')."</td>";
		echo "<td>".$row['post_category']."</td>";
		echo "<td>".getAuthorName($row['post_author_id'])."</td>";
		echo "</tr>"; 
	}
	echo "</table>";
	echo "</a>";
	echo "</div>"; //Fetch array and plot in table
}

// if ($_SESSION['role_user'] === "admin"){
	// echo "<style>#leftcolumn{width:50%;} #rightcolumn{width:50%;}</style>";
// } else if ($_SESSION['role_user'] === "power user"){
	// echo "<style>#leftcolumn{width:100%;} #rightcolumn{display:none;}</style>";
// } else {
	// echo "<script type='text/javascript'>
	// alert('Insufficient privileges for access!');
	// window.location='/websiteTestEJ/?page_id=88';
	// </script>";
	// exit;
// }
?>

<body>
<div id = "marginoverall">
<div class="row">
<div class="col-9 col-m-12">
	<div id = "leftcolumn">
		<div class="tab">
			<button class="tablinks" onclick="openTab(event, 'Product')" id="defaultOpen">My Contents</button>
			<?php
			if ($_SESSION['role_user']==="admin") {
			?>
			<button class="tablinks" onclick="openTab(event, 'General')">Contents Pending for Delete</button>
			<?php } ?>
		</div>
	
		<div id="Product" class="tabcontent">
			<?php
			$postdeleteresult = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE post_author_id='$uid' ORDER BY post_id DESC");
			$status = "1";
			viewTable($postdeleteresult, $status);
			?>
		</div>
	
		<div id="General" class="tabcontent">
			<?php
			$postresult = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE post_status='delete' ORDER BY post_id DESC");
			$status = "2";
			viewTable($postresult, $status);
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
	elLink.href = "?page_id=48?id="+ "<?php echo $row['post_id']; ?>";
}

var elems = document.getElementsByClassName('confirmation');
var confirmIt = function (e) {
	if (!confirm('Are you sure you want to delete?')) e.preventDefault();
};
for (var i = 0, l = elems.length; i < l; i++) {
    elems[i].addEventListener('click', confirmIt, false);
}
var elems = document.getElementsByClassName('confirmation2');
var confirmIt = function (e) {
	if (!confirm('Are you sure you want to remove from delete list?')) e.preventDefault();
};
for (var i = 0, l = elems.length; i < l; i++) {
    elems[i].addEventListener('click', confirmIt, false);
}
</script>


<?php get_footer();
} else {
	echo "<script type='text/javascript'>
	alert('Insufficient privileges for access.');
	window.location='/wordpress/?page_id=46';
	</script>";
	exit;
} 
?>