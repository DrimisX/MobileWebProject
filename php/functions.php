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
