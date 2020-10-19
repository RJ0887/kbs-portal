<?php /* Template Name: CreateEditpage */ ?>
<?php
// Start the session
session_start();
get_header();

// If not log in, go to login page
if(!(isset($_SESSION['login']))){
    echo "<script type='text/javascript'>
	alert('Insufficient privileges for access. Please log in.');
	window.location='/wordpress/?page_id=5';
	</script>";
	exit;
} else if (($_SESSION['role_user'] == "admin") || ($_SESSION['role_user'] == "power user")){
	include "connect.php";
	$sql_prod = "SELECT * FROM kbs_product_category";
	$sql_gen = "SELECT * FROM kbs_general_category";
	$result_prod = mysqli_query($conn, $sql_prod);
	$result_gen = mysqli_query($conn, $sql_gen);
	$pid = $_GET['pid'];
?>

<style>
div.createcontent{
	margin: 1em 5em 0em 5em;
}

#leftcolumn {
	float:left;
	width: 50%;
}

#innerleftcolumn {
	float:left;
	width: 50%;
}

#inner2leftcolumn {
	float:left;
	width: 30%;
}

#inner2rightcolumn {
	float:left;
	width: 70%;
	padding-left: 2em;
}

#innerrightcolumn {
	float:right;
	width: 50%;
	padding-left: 3em;
}

#rightcolumn {
	float:right;
	width: 50%;
	padding-left: 3em;
}
</style>

<script>
// toggle field between product and general
function toggleField(hideObj,showObj){
	hideObj.disabled=true;
	hideObj.style.display='none';
	showObj.disabled=false;
	showObj.style.display='inline';
	showObj.focus();
}

function displayForm() {
	document.getElementById('type').click();
    if ((document.formtype.type.value == "product")) {
		document.getElementById("rightcolumn").style.display = 'block';
		document.getElementById("category").style.display = 'block';
		document.getElementById("category2").style.display = 'none';
    } else if ((document.formtype.type.value == "general")) {
        document.getElementById("rightcolumn").style.display = 'none';
		document.getElementById("category").style.display = 'none';
		document.getElementById("category2").style.display = 'block';
    }
	else if (document.formtype.type.value == "") {
		document.getElementById("rightcolumn").style.display = 'none';
	}
}

function updateTextInput(val) {
	switch(val){
		case "0":
			document.getElementById('textOutput').value="not critical";
			break;
		case "1":
			document.getElementById('textOutput').value="low";
			break;
		case "2":
			document.getElementById('textOutput').value="medium";
			break;
		case "3":
			document.getElementById('textOutput').value="high";
			break;
	}
}

var elems = document.getElementsByClassName('confirmation');
var confirmIt = function (e) {
    if (!confirm('Are you sure you want to delete?')) e.preventDefault();
};
for (var i = 0, l = elems.length; i < l; i++) {
    elems[i].addEventListener('click', confirmIt, false);
}

function insertTextAtCursor(el, text) {
    var val = el.value, endIndex, range;
    if (typeof el.selectionStart != "undefined" && typeof el.selectionEnd != "undefined") {
        endIndex = el.selectionEnd;
        el.value = val.slice(0, el.selectionStart) + text + val.slice(endIndex);
        el.selectionStart = el.selectionEnd = endIndex + text.length;
    } else if (typeof document.selection != "undefined" && typeof document.selection.createRange != "undefined") {
        el.focus();
        range = document.selection.createRange();
        range.collapse(false);
        range.text = text;
        range.select();
    }
}

// function getInputSelection(el) {
    // var start = 0, end = 0, normalizedValue, range,
        // textInputRange, len, endRange;

    // if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        // start = el.selectionStart;
        // end = el.selectionEnd;
    // } else {
        // range = document.selection.createRange();

        // if (range && range.parentElement() == el) {
            // len = el.value.length;
            // normalizedValue = el.value.replace(/\r\n/g, "\n");

            // // Create a working TextRange that lives only in the input
            // textInputRange = el.createTextRange();
            // textInputRange.moveToBookmark(range.getBookmark());

            // // Check if the start and end of the selection are at the very end
            // // of the input, since moveStart/moveEnd doesn't return what we want
            // // in those cases
            // endRange = el.createTextRange();
            // endRange.collapse(false);

            // if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                // start = end = len;
            // } else {
                // start = -textInputRange.moveStart("character", -len);
                // start += normalizedValue.slice(0, start).split("\n").length - 1;

                // if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    // end = len;
                // } else {
                    // end = -textInputRange.moveEnd("character", -len);
                    // end += normalizedValue.slice(0, end).split("\n").length - 1;
                // }
            // }
        // }
    // }

    // return {
        // start: start,
        // end: end
    // };
// }

// function offsetToRangeCharacterMove(el, offset) {
    // return offset - (el.value.slice(0, offset).split("\r\n").length - 1);
// }

// function setSelection(el, start, end) {
    // if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        // el.selectionStart = start;
        // el.selectionEnd = end;
    // } else if (typeof el.createTextRange != "undefined") {
        // var range = el.createTextRange();
        // var startCharMove = offsetToRangeCharacterMove(el, start);
        // range.collapse(true);
        // if (start == end) {
            // range.move("character", startCharMove);
        // } else {
            // range.moveEnd("character", offsetToRangeCharacterMove(el, end));
            // range.moveStart("character", startCharMove);
        // }
        // range.select();
    // }
// }

// function insertTextAtCaret(el, text) {
    // var pos = getInputSelection(el).end;
    // var newPos = pos + text.length;
    // var val = el.value;
    // el.value = val.slice(0, pos) + text + val.slice(pos);
    // setSelection(el, newPos, newPos);
// }

function myFunction() {
    var textarea = document.getElementById("content");
	insertTextAtCursor(textarea, "[INSERTED]");
}
</script>

<?php
// check is it create or edit
// EDIT:
if ($pid != null){
	$postresult = mysqli_query($conn, "SELECT * FROM kbs_posts WHERE post_id='$pid'");
	$row = mysqli_fetch_array($postresult,MYSQLI_ASSOC);
	$type = $row['post_type'];

	// load values to specific post type
	$post_title = $row['post_title'];
	$post_critlvl = $row['post_critlvl'];
	$post_type = $row['post_type'];
	$post_content = $row['post_content'];
	$post_category = $row['post_category'];

	if ($row['post_type'] == "product"){
		echo "<style>#rightcolumn{display:block;}</style>";
		echo "<style>#category{display:block;}</style>";
		echo "<style>#category2{display:none;}</style>";
		// explode product info from rest of content
		// for every br:
		list($prodver, $prodenv, $prodcomp) = explode("<br>", $post_content);

		list($prodinfo, $prodver) = explode(": ", $prodver);
		list($prodinfo, $prodenv) = explode(": ", $prodenv);
		list($prodinfo, $prodcomp) = explode(": ", $prodcomp);

		// if all not filled
		if (empty($prodver) && empty($prodenv) && empty($prodcomp)) {
			$content = str_replace('<br />','',$post_content);
		} else if (empty($prodenv)) {
			// if first part contradicts with colon symbol
			$content = str_replace('<br />','',$post_content);
			$prodver="";
		} else {

			if (empty($prodver)) { $prodver=""; }
			if (empty($prodenv)) { $prodenv=""; }
			if (empty($prodcomp)) { $prodcomp=""; }
			list($prodinfo, $content) = explode("<br><br>", $post_content, 2);

			$content = str_replace('<br />','',$content);
		}

	} else if ($row['post_type'] == "general"){
		echo "<style>#rightcolumn{display:none;}</style>";
		echo "<style>#category2{display:block;}</style>";
		echo "<style>#category{display:none;}</style>";
		$content = str_replace('<br />','',$post_content);
	}
	$pagetitle = "Edit Content";

// CREATE:
} else {
	$post_title = $post_critlvl = $post_type = $post_content = $post_category = $prodver = $prodenv = $prodcomp = "";
	$pagetitle = "Create Content";
}
?>

<div class="createcontent">
    <h6><?php echo $pagetitle; ?></h6><br>
    <form name="formtype" style="width: 100%;" action="/wordpress/wp-content/kbs_post.php?testid=<?php echo $pid;?>&pid=<?php echo $pid;?>" method="post" id="contentform" class='confirmation' enctype="multipart/form-data">
		<div id = "leftcolumn">
			<input id="title" name="title" type="text" placeholder="Title" value = "<?php echo $post_title; ?>" <?= $post_title ? 'readonly="true"' : '' ?>><br>
			<div id = "innerleftcolumn">
				<div id = "inner2leftcolumn">
					Type: <br><select class="selectType" name="type" id="type" onclick="displayForm()" required>
					<?php
					if ($post_type == "product"){
						echo "<option selected='selected' value='product'>Product</option>";
						echo "<option value='general'>General</option>";
					} else if ($post_type == "general"){
						echo "<option value='product'>Product</option>";
						echo "<option selected='selected' value='general'>General</option>";
					} else if ($post_type == ""){
						echo "<option></option>
								<option value='product'>Product</option>
								<option value='general'>General</option>";
						}
					?>
					</select><br><br>
				</div>
				<div id = "inner2rightcolumn">
					Category:<br>
					<select id="category" name="category" onchange="if(this.options[this.selectedIndex].value=='customOption'){
						toggleField(this,this.nextSibling);this.selectedIndex='0';}">
						<option></option>
						<?php
						while ($row = mysqli_fetch_array($result_prod)){
							if ($post_category == ""){
								echo '<option>'.$row['category'].'</option>';
							} else {
								if ($post_category == $row['category']){
									echo "<option selected='selected'>".$row['category']."</option>";
								} else {
									echo '<option>'.$row['category'].'</option>';
								}
							}
						}
						?>
						<option value="customOption">[add new category]</option>
					</select><input name="category" style="display:none;" type="text" disabled="disabled"
					onblur="if(this.value==''){toggleField(this,this.previousSibling);}" >

					<select id="category2" name="category2" style="display:none;" onchange="if(this.options[this.selectedIndex].value=='customOption'){
						toggleField(this,this.nextSibling);this.selectedIndex='0'; }">
						<option></option>
						<?php
						while ($row = mysqli_fetch_array($result_gen)){
							if ($post_category == ""){
								echo '<option>'.$row['category'].'</option>';
							} else {
								if ($post_category == $row['category']){
									echo "<option selected='selected'>".$row['category']."</option>";
								} else {
									echo '<option>'.$row['category'].'</option>';
								}
							}
						}
						?>
						<option value="customOption">[add new category]</option>
					</select><input name="category2" style="display:none;" type="text" disabled="disabled"
					onblur="if(this.value==''){toggleField(this,this.previousSibling);}" >
					<br><br>
				</div>
				<?php
				if ($pid == null){
					echo "Add media:<input onchange='ValidateSize(this)' name='files[]' type='file' class='inputFile' multiple/><br><br>";
				} else {
					echo "Add media: (select previous media upload as well and any new media)<input onchange='ValidateSize(this)' name='files[]' type='file' class='inputFile' multiple/><br><br>";
				}
				?>
			</div>

			<div id = "innerrightcolumn">
				Critical Level:<label for="rangeinput" style="display:none;" >Range</label>
				<input id="critlvl" name="critlvl" type="range" min="0" max="3" value="<?php echo $post_critlvl; ?>" onchange="updateTextInput(this.value);" required></input>
				<output id="textOutput" value=""><br><br>
			</div>
		</div>

		<div id = "rightcolumn" style="display:none;">
			<input id="version" name="version" type="text" placeholder="Product Version" value="<?php echo $prodver; ?>" ><br>
			<input id="environment" name="environment" type="text" placeholder="Product Environment" value="<?php echo $prodenv; ?>"><br>
			<input id="component" name="component" type="text" placeholder="Component" value="<?php echo $prodcomp; ?>"><br><br>
		</div>

		<br>
		<textarea style="width: 100%;" rows="10" style="overflow:auto" id="content" name="content" form="contentform"
		placeholder="Content. To insert image at desired location, type in the image file name i.e. imagename.jpg at the desired content location. "
		required=""><?php echo $content; ?></textarea>
		<br><br>
		<input type="submit" value="Submit" />
    </form>
    <br>
</div>

<?php get_footer();
} else {
	echo "<script type='text/javascript'>
	alert('Insufficient privileges for access.');
	window.location='/websiteTestEJ/?page_id=46';
	</script>";
	exit;
}
?>
<script>
function ValidateSize(file) {
	var k = file.files.length;
	for (var i = 0; i < k; i++) {
		var FileSize = file.files[i].size / 1024 / 1024; // in MB
		if (FileSize > 10) {
			alert('Each file size must be less than 10MB.');
			file.value=null;
		} else {
		}
	}
}
</script>
