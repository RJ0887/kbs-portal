<?php /* 
Template Name: Contentpage
Template Post Type: post, page, event
 */ ?>
 

 
<?php
// Start the session
session_start();
define( 'WP_USE_THEMES', false );
get_header(); 
$author = $_SESSION['curr_user'];
$uid = $_SESSION['uid'];

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
$id = $_GET['id']; $pid = $_GET['pid'];

function getAuthorName($aid) {
	include "connect.php";
	$authorResult = mysqli_query($conn, "SELECT * FROM kbs_users WHERE user_id='$aid'");
	while ($row = mysqli_fetch_array($authorResult)) { 
		$authorName = $row['user_name'];
	}
	return $authorName;	
}
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

/************************** COMMENT LAYOUT *********************************/
.comment{
	border-top: 1px solid #ccc;
	padding-top: 20px;
}

.commentcontent {
    padding: 0px 0px 12px 0px;
    border: 1px solid #ccc;
	overflow: auto;
	background-color: white;
}

.commentcont {
	padding-left: 14px;
}

.commentauthor {
	background-color: #f2f2f2;
	padding: 5px 0px 5px 0px;
	border-bottom: 1px solid #ccc;
	margin-bottom: 10px;
}

/************************** RATING LAYOUT *********************************/
.rating {
    float:left;
	height: 90px;
}

/* :not(:checked) is a filter, so that browsers that don’t support :checked don’t 
   follow these rules. Every browser that supports :checked also supports :not(), so
   it doesn’t make the test unnecessarily selective */
.rating:not(:checked) > input {
    position:absolute;
    clip:rect(0,0,0,0);
}

.rating:not(:checked) > label {
    float:right;
    width:1em;
    padding:0 .2em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:200%;
    line-height:1.2;
    color:#ddd;
    text-shadow:1px 1px #bbb, 2px 2px #666, .1em .1em .2em rgba(0,0,0,.5);
}

.rating:not(:checked) > label:before {
    content: '★';
}

.rating  input:checked ~ label {
    color: #f70;
    text-shadow:1px 1px #c60, 2px 2px #940, .1em .1em .2em rgba(0,0,0,.5);
}

.rating:not(:checked) > label:hover,
.rating:not(:checked) > label:hover ~ label {
    color: gold;
    text-shadow:1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0,0,0,.5);
}

.rating > input:checked + label:hover,
.rating > input:checked + label:hover ~ label,
.rating > input:checked ~ label:hover,
.rating > input:checked ~ label:hover ~ label,
.rating > label:hover ~ input:checked ~ label {
    color: #ea0;
    text-shadow:1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0,0,0,.5);
}

.rating > label:active {
    position:relative;
    top:2px;
    left:2px;
}

h5 {
	font-size: 10px;	
}
</style>
</head>
<body>
<div id = "marginoverall">
<div class="row">
<div class="col-10 col-m-12">
	<div id = "leftcolumn">
		<?php
		$i=0;
		// Show images if any
		$imageresult = mysqli_query($conn, "SELECT * FROM kbs_uploads WHERE post_id='$pid'");
		if (mysqli_num_rows($imageresult)!==0) {
			$imagePresent = 1;
			while ($row = mysqli_fetch_assoc($imageresult)) { 
				$imageName[$i] = $row['upload_name'];
				$imageData[$i] = $row['upload_data'];
				$imageType[$i] = $row['upload_type'];		
				// echo "<br><br><img src='data:".$ImageType[$i].";base64," .$imageData[$i] . "' />";			
				$i++;
			}   
		} else {
			$imagePresent = 0;
		}
		$attach = 0;
		// Show content
		$contentresult = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE post_id='$pid'");
		while ($row = mysqli_fetch_array($contentresult)) { 
			echo "<h1>".$row['post_title']."</h1>";
			$date = new DateTime($row['post_date']);
			$newdate = new DateTime($row['post_modified']);
			$aid = ($row['post_author_id']);
			$authorname = getAuthorName($aid);
			$authorresult = mysqli_query($conn, "SELECT user_email FROM kbs_users WHERE user_id='$aid'");
			$row2 = mysqli_fetch_assoc($authorresult);
			echo "<h5>By "."<a href='mailto:".$row2['user_email']."' target='_top'>".$authorname."</a> | ".date_format($date, 'F jS, Y, g:ia')." MYT<br>";
			if ($row['post_date'] != $row['post_modified']){
				echo "Last modified: ".date_format($newdate, 'g:i:sa, jS F Y')."<br>";
			}
			echo "<i>Category: ".$row['post_category']."</i></h5><br>";
			// echo "<p>".$row['post_content']."</p>";
			
			$content = $row['post_content'];
			
			// loop through entire content here:
			for ($j=0; $j<strlen($content); $j++){
				if ($imagePresent == 1) {
				
					// make k num of windows according k num of images
					for ($k=0; $k<sizeof($imageName); $k++) {
						$window[$k] = substr($content,$j,strlen($imageName[$k]));
						// check window k same as each images' names
						for ($l=0; $l<sizeof($imageName); $l++) {
							// check whether window same as the image name
							// if yes, set flag and break loop
							if (strcasecmp($window[$k], $imageName[$l]) == 0){
							//if ($window[$k] == $imageName[$l]){
								$flag = 1;
								$imageNum = $l;
								break;
							}
						}
					}
				}
				
				
				// check flag, if flag=1, then show image, else show content
				if ($flag == 1){
					// check if image or attachment
					echo "<img style='height:auto;width:auto;' src='data:".$imageType[$imageNum].";base64," .$imageData[$imageNum] . "' />";
					$j+=strlen($imageName[$imageNum])-1;
					$flag = 0;
					
				} else {
					echo $content[$j];
				}
			}
			$string = "";
			if ($imagePresent == 1) {

				for ($m=0; $m<sizeof($imageName); $m++) {
					if (substr($imageType[$m], 0, 5) !== "image"){
						$flagUpload = 1;
						$string = "<a target='_blank' href='/wordpress/wp-content/viewPDF.php?pid=".$pid."&uploadName=".$imageName[$m]."'>".$imageName[$m]."<a><br>".$string;
					}
				}
			}
			if ($flagUpload == 1) {
				echo "<br><br><h4>Attachments:</h4>";
				echo $string;
			}
			
			echo "<br><br><br>";
		}
		
		// Rate
		if ($authorname != $author){
			// to avoid self-rating
	
			// Get rating value if set by user already:
			$rateresult = mysqli_query($conn, "SELECT * FROM kbs_rating WHERE post_id='$pid' AND rating_author_id='$uid'");
			while($row2 = mysqli_fetch_assoc($rateresult)) {
				$rateval = $row2['rating'];
			}
		
			$msg[1] = "Bad!";$msg[2] = "Kinda Bad";$msg[3] = "Moderate";$msg[4] = "Good";$msg[5] = "Very good!";	

			echo '<form action="/wordpress/wp-content/kbs_rate.php" method="post">
			<fieldset class="rating">
				<legend><b>Please rate:</b></legend>';			
				if ($rateval > 0){
					// need to be in decreasing manner (5 to 1)
					for ($m=5; $m>0; $m--){
						if ($m != $rateval){
							echo '<input type="radio" id="star'.$m.'" name="rating" value="'.$m.'"/><label for="star'.$m.'" title="'.$msg[$m].'">'.$m.' star</label>';
						} else {
							echo '<input type="radio" id="star'.$m.'" name="rating" value="'.$m.'" checked="checked"/><label for="star'.$m.'" title="'.$msg[$m].'">'.$m.' star</label>';
						}
					}	
				} else {
					for ($m=5; $m>0; $m--){				
						echo '<input type="radio" id="star'.$m.'" name="rating" value="'.$m.'"/><label for="star'.$m.'" title="'.$msg[$m].'">'.$m.' star</label>';
					}
				}
			?>
			</fieldset>
			<input type="hidden" name="pid" value="<?php echo $pid; ?>">
			<br><br><br><br><br><input type='submit' value='Submit' />
			</form><br><br>
		<?php
		}

		// Show comments if any
		$commentresult = mysqli_query($conn, "SELECT * FROM kbs_comment WHERE post_id='$pid'");
		$num = mysqli_num_rows($commentresult);
		if ($num <= "1"){
			echo "<h3>".$num." Comment</h3>";
		} else {
			echo "<h3>".$num." Comments</h3>";
		}

		while ($row = mysqli_fetch_array($commentresult)) {
			echo "<div class='commentcontent'>";
			echo "<div class='commentauthor'>";
			$date = new DateTime($row['comment_date']);
			echo "<b style='color:#3a3a3a;'>&nbsp&nbsp&nbsp".getAuthorName($row['comment_author_id'])."</b>&nbsp&nbsp&nbsp&nbsp<i style='font-size:12px;'>".date_format($date, 'F jS, Y, g:ia')."</i><br>";
			echo "</div><div class='commentcont'>";
			echo $row['comment']."<br>";
			if ($row['comment_author'] == $author){
				echo "<i style='font-size:14px;'>[<a href='/wordpress/wp-content/kbs_add-delete.php?pid=".$row['post_id']."&pid=".$row['post_id']."&udel=".$row['ID']."&opt=7' class='confirmation'>Delete</a>]</i>";
			}
			echo "</div></div><br>";
		}
		?>
		<h3>Leave a Comment:</h3>
		<form action="/wordpress/wp-content/kbs_comment.php" method="post">
			<textarea style="width: 100%;" rows="10" style="overflow:auto" name="comment" placeholder="Comment" required></textarea><br>
			<input type="hidden" name="pid" value="<?php echo $pid; ?>">
			<input type="submit" value="Submit" />
		</form>
	</div>
	</div>
</div>
<?php// include "page-sidebar.php";  ?>
</div>
</div>
</body>
</html>

<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>
<?php get_footer();
}?>