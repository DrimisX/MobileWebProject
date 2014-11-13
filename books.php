<?php
session_start();
include_once("php/constants.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<title>A Novel Concept</title>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="css/custom.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			// Initializes the carousel
			$(".start-slide").click(function(){
				$("#myCarousel").carousel('cycle');
			});
			// Stops the carousel
			$(".pause-slide").click(function(){
				$("#myCarousel").carousel('pause');
			});
		});
	</script>
  </head>
  <body>

	<!-- Modals -->
	<div class="modal fade" id="loginModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Log-in</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="exampleInputEmail1">Email address</label>
						<input class="form-control" id="exampleInputEmail1" placeholder="Enter email" type="email">
					</div>
					<div class="form-group">
						<label for="exampleInputPassword1">Password</label>
						<input class="form-control" id="exampleInputPassword1" placeholder="Password" type="password">
					</div>
					<p class="text-left"><a href="#">Forgot password?</a></p>
					<p class="text-right">New User? <a href="#">Sign Up</a> for a free account.</p>
				</div>
				<div class="modal-footer">
					<a href="#" data-dismiss="modal" class="btn">Close</a>
					<a href="member.php" class="btn btn-primary">Log-in</a>
				</div>
		    </div>
		</div>
	</div>
	
	<div class="modal fade" id="cartModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Shopping Cart</h4>
				</div>
				<div class="modal-body">
					<p>CART ITEMS HERE</p>
				</div>
				<div class="modal-footer">
					<a href="#" data-dismiss="modal" class="btn">Close</a>
					<a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
				</div>
		    </div>
		</div>
	</div>

	<div class="modal fade" id="bookModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
					<?php
					if(isset($_REQUEST['book_id'])) {
						$book_id = $_REQUEST['book_id'];
					} else {
						$book_id = 1;
					}
					$stmt = "SELECT b.book_id,book_title,book_plot,book_price,author_last,author_first,author_middle FROM books b";
					$stmt = $stmt." JOIN book_authors j ON b.book_id=j.book_id JOIN authors a ON j.author_id=a.author_id";
					$where = " WHERE b.book_id=".$book_id;
					$orderby = "book_title";
	
					$stmt .= $where." ORDER BY ".$orderby;
					$results = mysqli_query($con, $stmt);
					if(mysqli_errno($con)) {
						die("Could not select bookid ".$book_id.".<br>Query: ".$stmt."<br>Error: ".mysqli_error($con));
					}

					$book_title = $book_author = $book_plot = "---";
					$book_price = 0;
					if($row = mysqli_fetch_array($results)) {
						$book_title = $row['book_title'];
						$book_author = $row['author_first']." ".$row['author_middle']." ".$row['author_last'];
						$book_plot = $row['book_plot'];
						$book_price = $row['book_price'];
					} else {
						echo "Error parsing row.<br>";
					}
					echo $book_title;
					echo "</h4></div>\n";
					echo "<div class=\"modal-body\">";
					echo "<img src=\"images/";
					if(file_exists("images/".$book_id.".jpg")) {
						echo $book_id;
					} else {
						echo "coverart";
					}
					echo ".jpg\">";
					echo "<span class=\"singlebookdetails\">Author: ".$book_author."<br>";
					echo "Plot: ".$book_plot."<br>";
					printf("Price: $%.2f</span><br>",$book_price);
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Navigation -->
	<nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="#" class="navbar-brand">ANC</a>
		</div>
		<!-- Collection of nav links, forms, and other content for toggling -->
		<div id="navbarCollapse" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="books.php"><span class="glyphicon glyphicon-book"></span> Books</a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-list"></span> Categories<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="index.php#">Top Sellers</a></li>
						<li><a href="index.php#">What's New</a></li>
						<li><a href="index.php#">Featured Author</a></li>
						<li class="divider"></li>
						<li><a href="index.php#">See All</a></li>
					</ul>
				</li>
			</ul>
			<form role="search" class="navbar-form navbar-left">
				<div class="form-group">
					<input type="text" placeholder="Search by Author/Title/Keyword" class="input-large form-control" >
				</div>
			</form>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-info-sign"></span> Info<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="info.php#about">About Us</a></li>
						<li><a href="info.php#delivery">Delivery Information</a></li>
						<li><a href="info.php#privacy">Privacy Policy</a></li>
						<li><a href="info.php#terms">Terms & Conditions</a></li>
						<li class="divider"></li>
						<li><a href="contact.php">Contact Us</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="#cartModal" data-toggle="modal" data-target="#cartModal"><span class="glyphicon glyphicon-search"></span> View Items <span class="badge">0</span></a></li>
						<li class="divider"></li>
						<li><a href="checkout.php"><span class="glyphicon glyphicon-tag"></span> Checkout</a></li>
					</ul>
				</li>
				<li><a href="#loginModal" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-user"></span> Log-in</a></li>
			</ul>
		</div>
	</nav>
	<!-- PHP code for display of books by Jeff Codling-->
	<?php
	$stmt = "SELECT b.book_id,book_title,book_plot,book_price,author_last,author_first,author_middle FROM books b";
	$stmt = $stmt." JOIN book_authors j ON b.book_id=j.book_id JOIN authors a ON j.author_id=a.author_id";
	$orderby = "book_title";
	
	$stmt .= " ORDER BY ".$orderby;
	$results = mysqli_query($con, $stmt);
	if(mysqli_errno($con)) {
		die("Could not select books and authors.<br>Query: ".$stmt."<br>Error: ".mysqli_error($con));
	}
	echo "<div class=\"booklist\">\n";
	$counter=1;
	while($row = mysqli_fetch_array($results)) {
		echo "<a href=\"?id=".$row['book_id']."\">";
		echo "<div class=\"col-sm-6 col-md-4 col-lg-3\">";
		echo "<h2><img src=\"images/";
		if(file_exists("images/".$row['book_id'].".jpg")) {
			echo $row['book_id'];
		} else {
			echo "coverart";
		}
		echo ".jpg\"></h2>\n";
		echo "<h3>".$row['book_title']."</h3>\n";
		echo "<p>".$row['author_first']." ".$row['author_last']."</p>";
		echo "</div>\n";
		if ($counter % 2 == 0) {
			echo "<div class=\"clearfix visible-sm-block\"></div>";
		}
		if ($counter % 3 == 0) {
			echo "<div class=\"clearfix visible-md-block\"></div>";
		}
		if ($counter % 4 == 0) {
			echo "<div class=\"clearfix visible-lg-block\"></div>";
		}
		$counter++;
	}
	echo "</div></a>\n";
	?>
	<?php
	include_once("php/endofpage.php");
	?>
</body>
<script>
</script>
</html>