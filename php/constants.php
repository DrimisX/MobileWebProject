<?php
// *********** PHP Server Credentials
define("SERVERNAME","team01project.db.6194647.hostedresource.com");
define("DATABASENAME","team01project");
define("USR","team01project");
define("PASS","Pass4team01!");

// *********** PHP Settings Variable List
$timelimit = 300;			// Amount of time in seconds that a client will stay logged in without doing something. (300 = 5 mins.)
$itemsPerPage = 20;		// Number of items to display per page

$con = mysqli_connect(constant('SERVERNAME'), constant('USR'), constant('PASS'), constant('DATABASENAME'));
if($con->connect_error) {
	die("Connection failed: " . $con->connect_error);
}
