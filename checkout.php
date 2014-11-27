<?php
session_start();
include_once("php/constants.php");
include_once("php/functions.php");

if(isset($_REQUEST['back'])) {
	header("Location: ".$rootpage);
}

if(isset($_REQUEST['emptycart'])) {
// 	Display_Error("Empty Cart.");
	EmptyCart();
	header("Location: books.php");
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
	<nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="books.php" class="navbar-brand">A<br>&nbsp;Novel<br>&nbsp;&nbsp;Concept</a>
			</div>
			<ul class="nav">
				<li class="navbar-text navbar-right">
					<span class="glyphicon glyphicon-user"><span><?php
					if(isset($_SESSION['clientid'])) {
						echo getUsername($con,$_SESSION['clientid']);
					} else {
						echo "Unknown";
					}
					?>
				</li>
			</ul>
		</div>
	</nav>
	
	<div class="col-md-2">&nbsp;</div>
	<div class="row checkout col-md-8">
		<button type="button" class="close"><a href="<?php echo $rootpage; ?>">x</a></button>
		<h2>Checkout</h2>
		<hr>
		<?php
			ShowCart($con,true);
		?>
		<hr>
		<form class="right" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<button class="btn btn-info" type="submit" name="back">Back</button>
			<button class="btn <?php echo isset($_SESSION['cart'])?"btn-primary":"btn-disabled"; ?>" type="submit" name="placeorder">Place Order</button>
			<button class="btn <?php echo isset($_SESSION['cart'])?"btn-danger":"btn-isabled"; ?>" type="submit"  name="emptycart">Empty Cart</button>
		</form>
	</div>
	<div class="col-md-2">&nbsp;</div>

  </body>
</html>                                  		