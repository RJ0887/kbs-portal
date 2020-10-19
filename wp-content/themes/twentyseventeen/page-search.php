<?php /* 
Template Name: Searchpage
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
} else {
	include "connect.php";
?>
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
/************************** TECH ALERTS *****************************/
/* Style the content - tech alert */

.tacontent th {
	color: #3F51B5;
	padding: 10px;
	border-bottom: 5px solid #3F51B5;
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

.titlehead {
	border-bottom: 1px solid #ddd;
}
</style>
</head>
<body>
<div id = "marginoverall">
<div class="row">
<div class="col-9 col-m-12">
	<div id = "leftcolumn">
		<h6 style="text-transform: capitalize;">Search results for: <?php echo $_POST['search'];?></h6>
		<div id="ta" class="tacontent">
			<?php
			$search = $_POST['search'];
			$searchresult = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE (post_title LIKE '%".$search."%') OR (post_content LIKE '%".$search."%') ORDER BY post_id DESC");
			echo "<div style='overflow-y:auto'>"; 
			echo "<table id='DataTable', border='1', class='widget widget_categories'>
				<tr> 
					<th>Title</th>
					<th>Date</th>
					<th>Category</th>
					<th>Author</th>
				</tr>";
			while ($row = mysqli_fetch_array($searchresult)) { 
				echo "<tr>"; 
				echo "<td>"."<b>"."<a href='/wordpress/?page_id=48?pid=".$row['post_id']."&pid=".$row['post_id']."'>".$row['post_title']."</a>".
				"</b><br><p>".implode(' ', array_slice(explode(' ', $row['post_content']), 0, 30))." ..."."</p></td>";
				$date = new DateTime($row['post_date']);
				echo "<td>".date_format($date, 'g:i:sa jS F Y')."</td>";
				echo "<td>".$row['post_category']."</td>";
				echo "<td>".getAuthorName($row['post_author_id'])."</td>";
				echo "</tr>"; 
			}
			echo "</table>";
			echo "</div>"; //Fetch array and plot in table
			?>
		</div>
	</div>
</div>
<?php include "page-sidebar.php";  ?>
</div>
</div>
</body>
</html>
<?php get_footer();
}?>