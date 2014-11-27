<?php
// 
// PHP Functions for Web Project
// 
// by: Jeff Codling
// 

// Display Error
function Display_Error($text) {
	echo "<div style=\"margin-top: 70px;\" ><div class=\"alert alert-danger\" role=\"alert\">".$text."</div></div>";
}

// getUser validates login information against the database
function getUser($con,$username,$password) {
	if($username == "" || $username == NULL || $password == "" || $password == NULL) {
		Display_Error("Username/Password cannot be blank.");
	} else {
		$stmt = "SELECT client_id,username,password FROM accounts";
		$results = mysqli_query($con, $stmt);
		if(mysqli_errno($con)) {
			Display_Error("Fatal: Could not select from accounts<br>\".$stmt.\"<br>\".mysqli_error($con)");
			$flag = "noconnect";
		} else {
			$flag = "na";
			while($row=mysqli_fetch_array($results)) {
				if($row['username'] == $username) {
					if($row['password'] == $password) {
						$flag = $row['client_id'];											// Found match so return client_id
						$_SESSION['clientid'] = $flag;
						$_SESSION['clientname'] = $row['username'];		// change to client name when clients table created
						$_SESSION['timestamp'] = time();								// login timeout current time
					} else {
						$flag = "wrong_password";
					}
					break;
				}
			}
		}
		return $flag;
	}
}

// Add User to accounts
function addUser($con,$username,$password) {
	$flag = "na";
	if($username == "" || $username == NULL || $password == "" || $password == NULL) {
		Display_Error("Username/Password cannot be blank.");
		$flag = "error";
	} else {
		$result = getUser($con,$username,$password);
		if($result != "na" || $result == "wrong_password") {
			Display_Error("Username ".$username." already exists.");
			$flag = "error";
		} else {
			$stmt = "SELECT client_id FROM accounts";
			$results = mysqli_query($con, $stmt);
			if(mysqli_errno($con)) {
				Display_Error("Fatal: Could not select from accounts<br>\".$stmt.\"<br>\".mysqli_error($con)");
				$flag = "error";
			} else {
				$highest = 0;
				while($row = mysqli_fetch_array($results)) {
					if($row['client_id'] > $highest)
						$highest = $row['client_id'];
				}
				$highest++;
				$stmt = "INSERT INTO accounts (client_id,username,password) VALUES (".$highest.",'".$username."','".$password."')";
				$results = mysqli_query($con, $stmt);
				if(mysqli_errno($con)) {
					Display_Error("Fatal: Could not insert into accounts<br>\".$stmt.\"<br>\".mysqli_error($con)");
					$flag = "error";
				} else {
					$flag = "done";
				}
			}
		}
	}
	return $flag;
}

// GetUsername gets the username from the client id
function getUsername($con,$clientid) {
	$name = "username";
	$stmt = "SELECT username FROM accounts WHERE client_id=".$clientid;
	$results = mysqli_query($con, $stmt);
	if(mysqli_errno($con)) {
		Display_Error("Fatal: Could not select username from accounts<br>\".$stmt.\"<br>\".mysqli_error($con)");
		$flag = "noconnect";
	} else {
		$row = mysqli_fetch_array($results);
		$name = $row['username'];
	}
	return $name;
}

// GetPassword gets the password from the client id
function getPassword($con,$clientid) {
	$name = "password";
	$stmt = "SELECT password FROM accounts WHERE client_id=".$clientid;
	$results = mysqli_query($con, $stmt);
	if(mysqli_errno($con)) {
		Display_Error("Fatal: Could not select password from accounts<br>\".$stmt.\"<br>\".mysqli_error($con)");
		$flag = "noconnect";
	} else {
		$row = mysqli_fetch_array($results);
		$name = $row['password'];
	}
	return $name;
}

function changeUserInfo($con,$username,$password) {
	$flag = "error";
	$clientid = $_SESSION['clientid'];
	$stmt = "UPDATE accounts SET password='".$password."' WHERE client_id=".$clientid;
	$results = mysqli_query($con, $stmt);
	if(mysqli_errno($con)) {
		Display_Error("Fatal: Could not modify row in accounts<br>\".$stmt.\"<br>\".mysqli_error($con)");
	} else {
		$flag = "success";
	}
	return $flag;
}

function ShowPaging($page,$numPages,$sortby,$searchbox) {
	echo "<div class=\"clearfix\">";
	echo "<ul class=\"pagination\">\n";
	echo "<li><a href=\"?page=0";
	if($sortby != NULL) {
		echo "&sortby=".$sortby;
	}
	if($searchbox != NULL) {
		echo "&searchbox=".$searchbox;
	}
	echo "\">First</a></li>\n";
	for($i = 0; $i <$numPages; $i++) {
		echo "<li";
		if($page == $i) {
			echo " class=\"active\"";
		}
		echo "><a href=\"?page=".$i;
		if($sortby != NULL) {
			echo "&sortby=".$sortby;
		}
		if($searchbox != NULL) {
			echo "&searchbox=".$searchbox;
		}
		echo "\">".($i+1)."</a></li>\n";
	}
	echo "<li><a href=\"?page=".($i-1);
	if($sortby != NULL) {
		echo "&sortby=".$sortby;
	}
	if($searchbox != NULL) {
		echo "&searchbox=".$searchbox;
	}
	echo "\">Last</a></li>\n";
	echo "</ul></div>\n";
}

function GetBookInfo($con,$bookid) {
	$stmt = "SELECT book_title,author_first,author_last,book_price FROM books b";
	$stmt .= " JOIN book_authors ba ON b.book_id=ba.book_id";
	$stmt .= " JOIN authors a ON ba.author_id=a.author_id";
	$stmt .= " WHERE b.book_id = '".$bookid."'";
	$results = mysqli_query($con, $stmt);
	if(!$results) {
		Display_Error("Could not get book info for bookid ".$bookid);
	}
	$result = mysqli_fetch_assoc($results);
	return $result;
}

function ShowCart($con,$cart) {
	if(isset($_SESSION['cart'])) {
		$cart = unserialize($_SESSION['cart']);
		$totalprice = 0;
		echo "<table class=\"table table-hover\">\n";
		echo "<thead>\n";
		echo "<tr>";
		echo "<th>Book ID</th>";
		echo "<th>Title</th>";
		echo "<th>Author</th>";
		echo "<th class=\"right\">Price</th>";
		if(!$cart) {
			echo "<th>&nbsp;</th>";
		}
		echo "</tr>\n";
		echo "</thead>\n";
		echo "<tbody>\n";
		for($i=0 ; $i<sizeof($cart) ; $i++) {
			$bookinfo = GetBookInfo($con,$cart[$i]);
			echo "<tr>";
			echo "<td>".$cart[$i]."</td>";
			echo "<td>".$bookinfo['book_title']."</td>";
			echo "<td>".$bookinfo['author_first']." ".$bookinfo['author_last']."</td>";
			printf("<td class=\"right\">$%4.2f</td>",$bookinfo['book_price']);
			if(!$cart) {
				echo "<td><button class=\"btn btn-danger btn-xs\" type=\"submit\" name=\"removecart\" value=\"".$cart[$i]."\">remove</button><td>";
			}
			echo "</tr>\n";
			$totalprice += $bookinfo['book_price'];
		}
		echo "<tr><td></td><td></td><td class=\"right\">Total:</td><td class=\"right\">";
		printf("$%4.2f",$totalprice);
		echo "</td>";
		if(!$cart) {
			echo "<td></td>";
		}
		echo "</tr>\n";
		echo "</tbody>\n";
		echo "</table>\n";
	} else {
		echo "<div class=\"alert alert-danger\">Shopping cart is empty. Add books to continue.</div>\n";
	}
}

function AddCart($bookid) {
	if(isset($_SESSION['cart'])) {
		$cart = unserialize($_SESSION['cart']);
		$cart[sizeof($cart)] = $bookid;
	} else {
		$cart = array();
		$cart[0] = $bookid;
	}
	$_SESSION['cart'] = serialize($cart);
}

function RemoveCart($bookid) {
	if(isset($_SESSION['cart'])) {
		$cart = unserialize($_SESSION['cart']);
		$counter = 0;
		for($i=0 ; $i<sizeof($cart) ; $i++) {
			if($cart[$i] != $bookid) {
				$newcart[$counter] = $cart[$i];
				$counter++;
			}
		}
		if(sizeof($newcart)==0) {
			unset($_SESSION['cart']);
			header("Location: ".$_SERVER['PHP_SELF']);
		} else {
			$_SESSION['cart'] = serialize($newcart);
		}
	} else {
		unset($_SESSION['cart']);
		header("Location: ".$_SERVER['PHP_SELF']);
	}
}

function EmptyCart() {
	unset($_SESSION['cart']);
	header("Location: ".$_SERVER['PHP_SELF']);
}

