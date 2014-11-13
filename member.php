<!DOCTYPE html>
<html lang="en">
  <head>
	<title>A Novel Concept</title>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		
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
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="glyphicon glyphicon-user"></span> Account<b class="caret"></b></a>
					<ul role="menu" class="dropdown-menu">
						<li><a href="wishList.html"><span class="glyphicon glyphicon-star-empty"></span> Wish List <span class="badge">0</span></a></li>
						<li><a href="account.html"><span class="glyphicon glyphicon-cog"></span> My Account</a></li>
						<li class="divider"></li>
						<li><a href="index.html"><span class="glyphicon glyphicon-off"></span> Sign Out</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
	
	<div id="myCarousel" class="carousel slide" data-interval="3000" data-ride="carousel">
    	<!-- Carousel indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
			<li data-target="#myCarousel" data-slide-to="4"></li>
			<li data-target="#myCarousel" data-slide-to="5"></li>
        </ol>   
       <!-- Carousel items -->
        <div class="carousel-inner">
            <div class="active item">
                <h2><img src="images/20.jpg"></h2>
                <div class="carousel-caption">
                  <h3>Dan Brown - The Da Vinci Code</h3>
                  <p>Tom Hanks reveals the unrelenting horrors of Christianity.</p>
                </div>
            </div>
            <div class="item">
                <h2><img src="images/56.jpg"></h2>
                <div class="carousel-caption">
                  <h3>Douglas Adams - The Hitchhiker's Guide to the Galaxy</h3>
                  <p>Spoiler: The Universe ends.</p>
                </div>
            </div>
            <div class="item">
                <h2><img src="images/57.jpg"></h2>
                <div class="carousel-caption">
                  <h3>Harper Lee - To Kill A Mockingbird</h3>
                  <p>A novel about two people who are obsessed with killing a mockingbird.</p>
                </div>
            </div>
			<div class="item">
                <h2><img src="images/68.jpg"></h2>
                <div class="carousel-caption">
                  <h3>William Golding - Lord of the Flies</h3>
                  <p>In this thrilling sequel, Jeff Goldblum becomes the leader of a band of insects.</p>
                </div>
            </div>
			<div class="item">
                <h2><img src="images/80.jpg"></h2>
                <div class="carousel-caption">
                  <h3>Orson Scott Card - Ender's Game</h3>
                  <p>All about moving blocks and staying out of the rain.</p>
                </div>
            </div>
        </div>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>
	<div class="control-buttons">
        <input type="button" class="btn btn-info start-slide" value="Start">
        <input type="button" class="btn btn-info pause-slide" value="Pause">
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