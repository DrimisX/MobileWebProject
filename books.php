<?php
session_start();
include_once("php/constants.php");
include_once("php/functions.php");

// Login Timeout
if(isset($_SESSION['timestamp'])) {
	if(time()>$timelimit+$_SESSION['timestamp']) {
		session_unset();
		session_destroy();
		header("Location: ".$_SERVER['PHP_SELF']);
	} else {
		$_SESSION['timestamp'] = time();
	}
}

// Logout
if(isset($_REQUEST['logout'])) {
	session_unset();
	session_destroy();
	header("Location: ".$_SERVER['PHP_SELF']);
}

// Display_Error(isset($_SESSION['clientid'])?"ClientID is set":"Not set");
// Display_Error(isset($_SESSION['clientname'])?"Client Name is set":"Not set");

// Login error handling
if(isset($_POST['login'])) {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$loginResult = getUser($con,$_POST['username'],$_POST['password']);
		if($loginResult == "noconnect") {
			Display_Error("Login failed: No database connection.");
		} else if($loginResult == "na") {
			Display_Error("Login failed: Username not found.");
		} else if($loginResult == "wrong_password") {
			Display_Error("Login failed: Password not valid");
		} else {
			header("Location: ".$_SERVER['PHP_SELF']);
		}
	} else {
		Display_Error("Login failed: Username/Password required.");
	}
}

// Register New USER
if(isset($_POST['register'])) {
	$result = addUser($con,$_POST['username'],$_POST['password']);
	if($result == "done") {
		header("Location: ".$_SERVER['PHP_SELF']);
	}
}

// Modify Account Information
if(isset($_POST['modify'])) {
	$result = "na";
	if(isset($_POST['newpass']) && isset($_POST['oldpass'])) {
		if($_POST['newpass'] == $_POST['oldpass']) {
			DisplayError("Old password matches new password. No change.");
		} else {
			if($_POST['oldpass'] == getPassword($con,$_SESSION['clientid'])) {
				$result = changeUserInfo($con,getUsername($con,$_SESSION['clientid']),$_POST['newpass']);
			} else {
				Display_Error("Old password did not match. No change.");
			}
		}
	}
	if($result === "success") {
		header("Location: ".$_SERVER['PHP_SELF']);
	} else {
		Display_Error("Could not modify user account.");
	}
}	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>A Novel Concept</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="css/custom.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</head>
<body>

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
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-list"></span> Sort By<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="?sortby=title">Title</a></li>
						<li><a href="?sortby=afirst">Author First Name</a></li>
						<li><a href="?sortby=alast">Author Last Name</a></li>
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
				<?php
				if(isset($_SESSION['clientid'])) {					// If user is logged in display their name
					?>
					<li><a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-user"></span>
					<?php echo $_SESSION['clientname']; ?> Account<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="wishList.html"><span class="glyphicon glyphicon-star-empty"></span> Wish List <span class="badge">0</span></a></li>
						<li><a href="#accountModal" data-toggle="modal" data-target="#accountModal"><span class="glyphicon glyphicon-cog"></span> My Account</a></li>
						<li class="divider"></li>
						<li><a href="?logout=TRUE"><span class="glyphicon glyphicon-off"></span> Sign Out</a></li>
					</ul></li>
					<?php
				} else {
					?>
					<li><a href="#loginModal" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-user"></span> Log-in</a></li>
					<?php
				}?>
			</ul>
		</div>
	</nav>
	
	<div class="bodycontainer">
		<!-- PHP code for display of books by Jeff Codling-->
		<?php
		$stmt = "SELECT b.book_id,book_title,book_plot,book_price,author_last,author_first,author_middle FROM books b";
		$stmt = $stmt." JOIN book_authors j ON b.book_id=j.book_id JOIN authors a ON j.author_id=a.author_id";
		if(isset($_REQUEST['sortby'])) {
			switch ($_REQUEST['sortby']) {
				case "afirst":
					$orderby = "author_first";
					break;
				case "alast":
					$orderby = "author_last";
					break;
				default:
					$orderby = "book_title";
			}
		} else {
			$orderby="book_title";
		}
		if(isset($_POST['id']) && !isset($_POST['back'])) {					// If ID set add WHERE clause to SQL
			$stmt .= " WHERE b.book_id=".$_POST['id'];
		} else {
			$stmt .= " ORDER BY ".$orderby;														// ORDER BY for sorting selection
		}
		$results = mysqli_query($con, $stmt);
		if(mysqli_errno($con)) {
			die("Could not select books and authors.<br>Query: ".$stmt."<br>Error: ".mysqli_error($con));
		}
		if(isset($_POST['id']) && !isset($_POST['back'])) {
			echo "<div class=\"singlebook clearfix\">";
			$row = mysqli_fetch_assoc($results);
			echo "<img src=\"images/";
			if(file_exists("images/".$row['book_id'].".jpg")) {
				echo $row['book_id'];
			} else {
				echo "coverart";
			}
			echo ".jpg\">";
			echo "<div class=\"booktitle\">".$row['book_title']."</div>";
			echo "<div class=\"bookauthor\">".$row['author_first']." ".$row['author_middle']." ".$row['author_last']."</div>";
			echo "<div class=\"bookplot\">".$row['book_plot']."</div>";
			printf("<div class=\"bookprice\">$%.2f</div>",$row['book_price']);
			echo "</div>";
			echo "<form action=\"\" method=\"post\">";
			echo "<button type=\"submit\" name=\"back\">Back</button>";
			echo "</form>";
		} else {
			echo "<div class=\"booklist\">\n";
			$counter=1;
			while($row = mysqli_fetch_array($results)) {
				echo "<a href=\"?id=".$row['book_id']."\">";
				echo "<div class=\"book col-xs-12 col-sm-6 col-md-4 col-lg-3\">";
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
		}
	?>
	<div>
	
		<!-- Modals -->
	<div class="modal fade" id="loginModal">								<!-- Login Modal -->
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Log-in</h4>
					</div>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="modal-body">
						<div class="form-group">
							<label for="exampleInputEmail1">Username</label>
							<input class="form-control" id="exampleInputEmail1" name="username" placeholder="Username" type="text">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Password</label>
							<input class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" type="password">
						</div>
						<p class="text-left"><a href="forgot.php">Forgot password?</a></p>
						<p class="text-right">New User? <a href="#regModal" data-toggle="modal" data-target="#regModal" onclick="$('#loginModal').modal('hide')">Sign Up</a> for a free account.</p>
					</div>
					<div class="modal-footer">
						<a href="#" data-dismiss="modal" class="btn">Close</a>
						<button type="submit" class="btn btn-success" name="login">Log-in</button>
					</div>
					</form>
		    </div>
		</div>
	</div>

	<div class="modal fade" id="regModal">								<!-- Register Modal -->
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Register</h4>
					</div>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="modal-body">
						<div class="form-group">
							<label for="exampleInputEmail1">Username</label>
							<input class="form-control" id="exampleInputEmail1" name="username" placeholder="Username" type="text">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Password</label>
							<input class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" type="password">
						</div>
					</div>
					<div class="modal-footer">
						<a href="#" data-dismiss="modal" class="btn">Cancel</a>
						<button type="submit" class="btn btn-success" name="register">Register</button>
					</div>
					</form>
		    </div>
		</div>
	</div>

	<div class="modal fade" id="accountModal">								<!-- Account Modal -->
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Account Management</h4>
					</div>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="modal-body">
						<div class="form-group">
							<label for="accuser">Username</label>
							<input class="form-control" id="accuser" name="accuser" value="<?php echo getUsername($con,$_SESSION['clientid']); ?>" type="text" disabled>
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Old Password</label>
							<input class="form-control" id="exampleInputPassword1" name="oldpass" placeholder="old password" type="password">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">New Password</label>
							<input class="form-control" id="exampleInputPassword1" name="newpass" placeholder="new password" type="password">
						</div>
					</div>
					<div class="modal-footer">
						<a href="#" data-dismiss="modal" class="btn">Cancel</a>
						<button type="submit" class="btn btn-success" name="modify">Modify</button>
						<button type="submit" class="btn btn-danger" name="delete">Delete</button>
					</div>
					</form>
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
	
	<?php
	include_once("php/endofpage.php");
	?>
</body>
</html>
