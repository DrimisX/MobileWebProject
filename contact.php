<!DOCTYPE html>
<html lang="en">
  <head>
	<title>Example of Twitter Bootstrap 3 Grid System</title>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
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
	<style type="text/css">
		h2{
			margin: 0;     
			color: #666;
			padding-top: 10px;
			font-size: 52px;
			font-family: "trebuchet ms", sans-serif;    
		}
		h2 img{
			
		}
		.item{
			background: url("images/woodWall.jpg");    
			text-align: center;
			height: 300px !important;
		}
		.carousel{
			margin-top: 20px;
		}
		.control-buttons{
			text-align:center;
		}
		.navbar-inverse .navbar-brand {
			background: url("images/mobileBrand.jpg");
			color: white;
		}
	</style>
	
  </head>
  <body>
	<nav role="navigation" class="navbar navbar-inverse">
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
				<li><a href="index.html"><span class="glyphicon glyphicon-book"></span> Books</a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-list"></span> Categories<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="index.html#">Top Sellers</a></li>
						<li><a href="index.html#">What's New</a></li>
						<li><a href="index.html#">Featured Author</a></li>
						<li class="divider"></li>
						<li><a href="index.html#">See All</a></li>
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
						<li><a href="info.html#about">About Us</a></li>
						<li><a href="info.html#delivery">Delivery Information</a></li>
						<li><a href="info.html#privacy">Privacy Policy</a></li>
						<li><a href="info.html#terms">Terms & Conditions</a></li>
						<li class="divider"></li>
						<li><a href="contact.html">Contact Us</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="#cartModal" data-toggle="modal" data-target="#cartModal"><span class="glyphicon glyphicon-search"></span> View Items <span class="badge">0</span></a></li>
						<li class="divider"></li>
						<li><a href="checkout.html"><span class="glyphicon glyphicon-tag"></span> Checkout</a></li>
					</ul>
				</li>
				<li><a href="#loginModal" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-user"></span> Log-in</a></li>
			</ul>
		</div>
	</nav>
	
	<h2>Contact Us</h2>
	
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
					<a href="member.html" class="btn btn-primary">Log-in</a>
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
					<a href="checkout.html" class="btn btn-primary">Proceed to Checkout</a>
				</div>
		    </div>
		</div>
	</div>
	
  </body>
</html>                                  		