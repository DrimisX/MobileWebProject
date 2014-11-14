<?php
// 
// PHP Functions for Web Project
// 
// by: Jeff Codling
// 

function Display_Error($text) {
	echo "<div style=\"margin-top: 70px;\" ><div class=\"alert alert-danger\" role=\"alert\">".$text."</div></div>";
}

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

