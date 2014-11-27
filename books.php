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

// Add book to cart
if(isset($_REQUEST['addcart'])) {
// 	Display_Error("Add book ".$_REQUEST['addcart']." to cart.");
	AddCart($_REQUEST['addcart']);
}
// Remove book from cart
if(isset($_REQUEST['removecart'])) {
// 	Display_Error("Remove book ".$_REQUEST['removecart']." to cart.");
	RemoveCart($_REQUEST['removecart']);
}
// Empty cart
if(isset($_REQUEST['emptycart'])) {
// 	Display_Error("Empty Cart.");
	EmptyCart();
}
// Checkout
if(isset($_REQUEST['checkout'])) {
	header("Location: checkout.php");
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
			<a href="#" class="navbar-brand">A<br>&nbsp;Novel<br>&nbsp;&nbsp;Concept</a>
		</div>
		<!-- Collection of nav links, forms, and other content for toggling -->
		<div id="navbarCollapse" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="#"><span class="glyphicon glyphicon-book"></span> Books</a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-list"></span><?php
					if(isset($_GET['sortby'])) {
						switch ($_GET['sortby']) {
							case "afirst":
								echo " By:Author's First Name";
								break;
							case "alast":
								echo " By:Author's Last Name";
								break;
							case "title":
								echo " By:Title";
								break;
							default:
								echo " Sort By";
						}
					} else {
						echo " Sort By";
					}
					?><b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="?sortby=title">Title</a></li>
						<li><a href="?sortby=afirst">Author First Name</a></li>
						<li><a href="?sortby=alast">Author Last Name</a></li>
					</ul>
				</li>
			</ul>
			<form role="search" class="navbar-form navbar-left" method="post">
				<div class="form-group">
					<input type="text" placeholder="Search <?php
					if(isset($_GET['sortby'])) {
						if($_GET['sortby'] == "afirst" || $_GET['sortby'] == "alast") {
							echo "Author's name";
						} else {
							echo "in Title";
						}
					} else {
						echo "in Title";
					}
					?>" class="input-large form-control" name="searchbox"
					<?php
					if(isset($_REQUEST['searchbox'])) {
						echo " value = \"".$_REQUEST['searchbox']."\"";
					}
					?>>
					<button class="btn " name="search" value="Search">Go</button>
				</div>
			</form>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="#infoModal" data-toggle="modal" data-target="#infoModal"><span class="glyphicon glyphicon-info-sign"></span> Info</a>
				</li>
<!-- 
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
 -->
				<?php
				if(isset($_SESSION['clientid'])) {
					?>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart<b class="caret"></b></a>
						<ul role="menu" class="dropdown-menu">
							<li><a href="#cartModal" data-toggle="modal" data-target="#cartModal"><span class="glyphicon glyphicon-search">
								</span> View Items <span class="badge">
								<?php
								if(isset($_SESSION['cart'])) {
									echo sizeof(unserialize($_SESSION['cart']));
								} else {
									echo "0";
								}
								?>
								</span></a></li>
							<li class="divider"></li>
							<li><a href="checkout.php"><span class="glyphicon glyphicon-tag"></span> Checkout</a></li>
						</ul>
					</li>
					<?php
				}
				if(isset($_SESSION['clientid'])) {					// If user is logged in display their name
					?>
					<li><a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-user"></span>
					<?php echo $_SESSION['clientname']; ?> Account<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
<!-- 						<li><a href="wishList.html"><span class="glyphicon glyphicon-star-empty"></span> Wish List <span class="badge">0</span></a></li> -->
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
		if(isset($_REQUEST['id']) && !isset($_REQUEST['back'])) {					// If ID set add WHERE clause to SQL
			$stmt .= " WHERE b.book_id=".$_REQUEST['id'];
		} else {
			if(isset($_REQUEST['searchbox'])) {
				if(isset($_GET['sortby'])) {
					switch ($_GET['sortby']) {
						case "afirst":
							$whereclause = "author_first";
							break;
						case "alast":
							$whereclause = "author_last";
							break;
						default:
							$whereclause = "book_title";
					}
				} else {
					$whereclause = "book_title";
				}
				$whereclause .= " LIKE '%".$_REQUEST['searchbox']."%'";
				$stmt .= " WHERE ".$whereclause;
			} else {
				$stmt .= " ORDER BY ".$orderby;														// ORDER BY for sorting selection
			}
		}
		if(isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 0;
		}
		if(isset($_POST['search'])) {
			$page = 0;
		}
		$results = mysqli_query($con, $stmt);
		if(mysqli_errno($con)) {
			die("Could not select books and authors.<br>Query: ".$stmt."<br>Error: ".mysqli_error($con));
		}
		$resultsCopy = $results;
		$numItems = mysqli_num_rows($results);
		$numPages = (int) ($numItems / $itemsPerPage);
		
		$stmt .= " LIMIT ".($page*$itemsPerPage).",".$itemsPerPage;
		$results = mysqli_query($con, $stmt);
		if(mysqli_errno($con)) {
			die("Could not select books and authors.<br>Query: ".$stmt."<br>Error: ".mysqli_error($con));
		}
			echo "<div class=\"booklist\">\n";
			$counter=1;
			if($numPages>1 && $itemsPerPage>5) {
				if(isset($_REQUEST['sortby'])) {
					$sortby = $_REQUEST['sortby'];
				} else {
					$sortby = NULL;
				}
				if(isset($_REQUEST['searchbox'])) {
					$searchbox = $_REQUEST['searchbox'];
				} else {
					$searchbox = NULL;
				}
				ShowPaging($page,$numPages,$sortby,$searchbox);
			}
			while($row = mysqli_fetch_array($results)) {
				echo "<a href=\"#book".$row['book_id']."Modal\" data-toggle=\"modal\" data-target=\"#book".$row['book_id']."Modal\">";
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
			echo "<div class=\"clearfix\">&nbsp;</div>";
			echo "<div class=\"bottompagination\">";
			if($numPages>1) {
				if(isset($_REQUEST['sortby'])) {
					$sortby = $_REQUEST['sortby'];
				} else {
					$sortby = NULL;
				}
				if(isset($_REQUEST['searchbox'])) {
					$searchbox = $_REQUEST['searchbox'];
				} else {
					$searchbox = NULL;
				}
				ShowPaging($page,$numPages,$sortby,$searchbox);
			}
			echo "<div>\n";
// 		}
	?>
	<div>
	
	<!-- Modals -->
	<?php
	while($row = mysqli_fetch_array($resultsCopy)) {									// Single Book View Modals
		?>
		<div class="modal fade" id="book<?php echo $row['book_id']; ?>Modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						<h4 class="modal-title"><?php echo $row['book_title']; ?></h4>
					</div>
					<div class="modal-body singlebook clearfix">
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
							<div class="col-xs-12 col-sm-6">
								<img class="bookimage" src="images/<?php
								if(file_exists("images/".$row['book_id'].".jpg")) {
									echo $row['book_id'];
								} else {
									echo "coverart";
								}
								?>.jpg">
							</div>
							<div class="col-xs-12 col-sm-6">
								<?php
								echo "<div class=\"booktitle\">".$row['book_title']."</div>";
								echo "<div class=\"bookauthor\">".$row['author_first']." ".$row['author_middle']." ".$row['author_last']."</div>";
								echo "<div class=\"bookplot\">".$row['book_plot']."</div>";
								echo "<br>";
								printf("<div class=\"bookprice\">$%.2f</div>",$row['book_price']);
								echo "</div>";
							echo "</div>";
							echo "<div class=\"modal-footer\">";
							echo "<button class=\"btn btn-sm";
								if(isset($_SESSION['clientid'])) {
									echo " btn-success";
								} else {
									echo " disabled";
								}
							echo "\" name=\"addcart\" value=\"".$row['book_id']."\">Add to Cart</button>";
							echo "<a href=\"#\" data-dismiss=\"modal\" class=\"btn\">Close</a>";
							echo "</div>";
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	
	<div class="modal fade" id="infoModal">							<!-- Info Model -->
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Company Information</h4>
				</div>
				<div class="modal-body">
					<div role="tabpanel">

						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#aboutus" aria-controls="aboutus" role="tab" data-toggle="tab">About Us</a></li>
							<li role="presentation"><a href="#policy" aria-controls="policy" role="tab" data-toggle="tab">Privacy Policy</a></li>
							<li role="presentation"><a href="#terms" aria-controls="terms" role="tab" data-toggle="tab">Terms of Use</a></li>
							<li role="presentation"><a href="#contact" aria-controls="contact" role="tab" data-toggle="tab">Contact</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content  scrollable">
							<div role="tabpanel" class="tab-pane active" id="aboutus">
								<div class="row">
									<div class="col-md-6">
										<h4>About Us</h4>
										<p><strong>A Novel Concept</strong> is a bookstore that was made as a web project for class in
											2nd year CPRO at Lambton College, Sarnia, Ontario, Canada.<p>
										<p>We sell books of all sorts, new and used.</p>
									</div>
									<div class="col-md-6">
										<h4>Delivery Address</h4>
										<address>
											<strong>A Novel Concept</strong><br>
											123 Fake Street<br>
											Clock Town, N0V 3L0<br>
											<abbr title="Phone">P:</abbr> 1-888-NOVE (6683)
										</address>
									</div>
								</div>
								<div class=".col-md-6 .col-md-offset-3">
									<p class="centre"><strong>This site is a proof of concept and not a real store.</strong></p>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="policy">
								<div class="row">
									<div class="col-xs-12">
										<h4>Privacy Policy</h4>
										<p><strong>A Novel Concept</strong>(also we, us and our) respects your privacy rights, as an online visitor, and recognizes the importance of protecting the information collected about you.
A Novel Concept collects personal information by ordering. 
											When you order you are consenting to the collection of your personal data. If your order is placed with us, we need to hold personal information including your name, email address, phone numbers, home address, shipping and credit/debit card billing address(es) so that we can process and fulfill your order.

											Saved card details will never be shared with third parties and will only be used to process your order, using our payment partnerís systems. Additionally we may also obtain information as a result of authentication or identity checks. Sometimes we may ask for your telephone number. 
											This number may be given to our courier for delivery services. These details allow us to process your order and to let you know the status of your order.
										</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="terms">
								<div class="row">
									<div class="col-xs-12">
										<h4>Terms and Conditions</h4>
										<p>These terms and conditions apply to all orders placed with <strong>A Novel Concept</strong>.
											The company will be referred to in these conditions as <strong>A Novel Concept</strong>, this website or we.
										</p>
										<p>PLEASE READ THESE TERMS AND CONDITIONS CAREFULLY BEFORE USING THIS WEBSITE. 
											YOUR USE OF THIS WEBSITE CONFIRMS YOUR ACCEPTANCE OF THE FOLLOWING TERMS AND CONDITIONS. 
											<strong>A Novel Concept</strong> MAY REVISE THESE TERMS AND CONDITIONS AT ANY TIME AND FORM TIME TO TIME BY UPDATING THIS POSTING.
										</p>
										<h4>Product Information</h4>
										<p>We attempt to ensure that information on this website is complete, accurate and current.</p>
										<p>Despite our efforts, the product information on this website may occasionally be inaccurate, incomplete
											or out of date. We make no representation as to the completeness, accuracy or currentness of any product
											information on this website.</p>
										<h4>Prices</h4>
										<p>Unless otherwise stated, the prices stated on this website include VAT but exclude delivery costs, which will be added to the total amount due. Prices are liable to change at any time. 
										The price at the time of making an order is valid throughout that buying process.</p>
										<h4>Payment</h4>
										<p>Orders will only be shipped after <strong>A Novel Concept</strong> has received complete payment for the product purchased. Payment will only be accepted by using the payment options outlined on this website.</p>
										<p>All credit/debit card holders are subject to validation checks and authorization by the card issuer. If the issuer of your payment card refuses to authorize payment, we will not be liable for any delay or non-delivery of your order. In the event that your card authorization and validation is declined, we reserve the right to cancel your order.</p>
										<p>When <strong>A Novel Concept</strong> receives no payment within 14 days after order date, the order will be canceled.</p>
										<h4>Delivery</h4>
										<p>The products offered by <strong>A Novel Concept</strong> on this website are available for countries within the European Union and for some countries outside of the EU. Customers who want to order outside of the listed countries on this site must send their order to <strong>A Novel Concept</strong> directly using: service@realfakestudios.com. After the customer gives their ok on shipping costs <strong>A Novel Concept</strong> will proceed with the order made.<p>
										<p>Ordered items will be made ready for dispatch by <strong>A Novel Concept</strong>, within 3 working days after the received order and will be shipped directly to the address you have stated as your delivery address by Post NL within the order time stated in the overview of our customer service. The delivery periods as stated on the website are indicative only. </p>
										<p><strong>A Novel Concept</strong> is not responsible for any delay in delivery due to postal delays, strikes or forces otherwise beyond our control.</p>
										<p>If you are not available at the address you have stated on your order form when our logistical partner delivers, they will leave a note
											indicating options to arrange new delivery time, or indicate the address of the nearest neighbour or post office the parcel has been delivered to.</p>
										<p><strong>Note:</strong> if the attempts by our logistical partner to deliver to the stated address is unsuccessful, the package will
											be shipped back to <strong>A Novel Concept</strong>. <strong>A Novel Concept</strong> is not liable to refund the freight cost and any extra costs made in the return process.</p>
										<p><strong>Note:</strong> International orders are subject to the custom fees charged by the destinationís government.
											<strong>A Novel Concept</strong> has no control or responsibility over any charges that may be applied by the destination country,
											like customs and import duties or sales tax.<p>
										<h4>Returns and Exchanges</h4>
										<p>If you donít like the item that you ordered, for any reason what so ever, we will refund your money with 100% (excluding delivery charges).</p>
										<p>Please send an email to: service@aNovelConcept.com and let us know your:</p>
										<dl>
											<dt>Return</dt>
											<dd>Order number</dd>
											<dd>Customer name</dd>
											<dd>Reason of return</dd>
										</dl>
										<h4>Defects</h4>
										<p>You are required to fully inspect the delivered items within 14 working days after delivery.</p>
										<p>In case of any alleged incompleteness or defect you must give written notice to <strong>A Novel Convept</strong> by sending an e-mail to
											service@NovelConcept.com within this period of 14 working days, after which no claims for deficiency or incompleteness will be accepted.</p>
										<p><strong>A Novel Convept</strong> cannot be held responsible for defects or damage caused by third parties, including logistical partners. Issues concerning
											third parties will be handled on a case-by-case basis.</p>
										<h4>Legal</h4>
										<p>These terms and conditions are governed by the laws of the Nowherelands and all claims and disputes between <strong>A Novel Convept</strong> and
											the purchaser will exclusively be brought before and settled by the competent court in Canada. <strong>A Novel Convept</strong> reserves the right
											to bring a claim before any other court.</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="contact">
								<div class="row">
									<div class="col-md-12">
										<h4>Contact Information</h4>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<address><strong>E-mail</strong><br>
											novel@NovelConcept.com<br><br>
											<strong>Customer service</strong><br>
											novelService@novelConcept.com<br><br>
											<strong>Phone:</strong> 1-888-NOVE (6683)<br>
											(available from 9AM to 6PM on working days)
										</address>
									</div>
									<div class="col-md-6">
										<address>
											<strong>A Novel Concept</strong><br>
											123 Fake Street<br>
											Clock Town, N0V 3L0<br>
										</address>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>" data-dismiss="modal" class="btn">Close</a>
				</div>
			</div>
		</div>
	</div>

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
							<input class="form-control" id="exampleInputEmail1" name="username" placeholder="Username" type="text" autofocus="true">
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

	<div class="modal fade" id="cartModal">							<!-- Cart Model -->
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Shopping Cart</h4>
				</div>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="modal-body">
						<?php
						ShowCart($con,false);
						?>
					</div>
					<div class="modal-footer">
							<a href="#" data-dismiss="modal" class="btn">Close</a>
							<button class="btn <?php echo isset($_SESSION['cart'])?"btn-primary":"btn-disabled"; ?>" type="submit" name="checkout">Proceed to Checkout</a>
							<button class="btn <?php echo isset($_SESSION['cart'])?"btn-danger":"btn-disabled"; ?>" type="submit" name="emptycart">Empty Cart</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
	include_once("php/endofpage.php");
	?>
</body>
</html>
