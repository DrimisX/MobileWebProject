<?php

// Web Project for 2nd year CPRO at Lambton College
// 
// A Novel Concept
//
// Created by:				Team 1
// Jeff Codling				HTML5, CSS3, PHP, MySQL Implementation
// Dylan Huculak			HTML5, CSS3, Sample Data
// Jason Preston			Database Design, Documentation, Sample Data
//

// *********** PHP Server Credentials
// Dylan's Hosting
//
// define("SERVERNAME","gator3119.hostgator.com");
// define("DATABASENAME","rbp_team01");
// define("USR","rbp_team01");
// define("PASS","Pass4team01!");

// Localhost - Local testing
//
// define("SERVERNAME","localhost");
// define("DATABASENAME","web_project");
// define("USR","root");
// define("PASS","");

// Jeff's 1&1 Hosting - Rock Solid (use with Jeff's 1&1 hosting)
//
define("SERVERNAME","db554781221.db.1and1.com");
define("DATABASENAME","db554781221");
define("USR","dbo554781221");
define("PASS","team01project!");

// Jeff's GoDaddy Hosting - Unreliable
//
// define("SERVERNAME","team01project.db.6194647.hostedresource.com");
// define("DATABASENAME","team01project");
// define("USR","team01project");
// define("PASS","Pass4team01!");

// *********** PHP Settings Variable List
$timelimit = 300;			// Amount of time in seconds that a client will stay logged in without doing something. (300 = 5 mins.)
$itemsPerPage = 12;		// Number of items to display per page
$rootpage = "books.php";		// Root of application

$con = mysqli_connect(constant('SERVERNAME'), constant('USR'), constant('PASS'), constant('DATABASENAME'));
if($con->connect_error) {
	Display_Error("Connection failed: " . $con->connect_error);
}

$stmt = "SELECT count(book_id) FROM books";
$results = mysqli_query($con, $stmt);
if(!$results) {
	Display_Error("Could not get count of books.");
}
$numRows = mysqli_fetch_array($results, MYSQLI_NUM);
$totalNumPages = (int)($numRows[0] / $itemsPerPage);
