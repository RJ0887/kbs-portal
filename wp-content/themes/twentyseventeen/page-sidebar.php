<style>

ul {
	text-transform: capitalize;
}

</style>

<div id = "marginoverall">
<div class="row">
<div class="col-3 col-m-12">
	<div id = "rightcolumn">

		<!--Search Bar-->
		<form method="post" class="search-form" action="/wordpress/?page_id=65">
			<input type="search" class="search-field" placeholder="Search ..." name="search" />
			<button type="submit" class="search-submit">
				<svg class="icon icon-search" aria-hidden="true" role="img">
					<use href="#icon-search" xlink:href="#icon-search"></use> 
				</svg>
			</button>
		</form><br><br>

		<section id="categories-2" class="widget widget_categories">
			<h2 class="widget-title" style="font-size:12px;">Categories</h2>
			<?php
				include "connect.php";
				echo "<h2 class='widget-title'>Product</h2>";
				$prodcategory = mysqli_query($conn, "SELECT * FROM kbs_product_category");
				$gencategory = mysqli_query($conn, "SELECT * FROM kbs_general_category");
				echo "<ul>";
				while ($row = mysqli_fetch_array($prodcategory)) { 
					echo "<li><a href='/wordpress/?page_id=44?id=".$row['category_id']."&cat=".$row['category']."'>".$row['category']."</a>"; 
				}
				echo "</ul>";
				echo "<br><h2 class='widget-title'>General</h2>";
				echo "<ul>";
				while ($row = mysqli_fetch_array($gencategory)) { 
					echo "<li><a href='/wordpress/?page_id=44?id=".$row['category_id']."&cat=".$row['category']."'>".$row['category']."</a>"; 
				}
				echo "</ul>";
			?>
		</section>
	</div>
</div>
</div>
<!--</div> -->