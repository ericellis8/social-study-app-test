<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start();
require('includes/mia.classes.php');
$mia = MiaChatDb::getInstance();
if ($mia->sessionHijackCheck()===false) {
    die('Invalid session, operation not permitted! Please <a href="index.php" target="_parent">login</a>.');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Mia-Chat Buddy Finder</title>
	<link rel="stylesheet" href="css/reset-fonts-grids.css" type="text/css" />
	<link rel="stylesheet" href="css/mia.css" type="text/css" media="screen" />
	<script type="text/javascript" src="includes/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="includes/js/mia/manageBuddy.js"></script>
</head>
<body>
<div id="doc3" class="yui-t7">
	<div id="hd"><h1>Mia-Chat Buddy Finder</h1></div>
	<div id="bd">
		<div id="addBuddyFrm" class="yui-g">
			<h2>Find A New Buddy</h2>
			<fieldset>
				<label for="username">Username:</label>
				<input id="username" name="username" type="text" size="25" />
				<label for="fullname">Full Name:</label>
				<input id="fullname" name="fullname" type="text" size="25" />
				<label for="email">Email Address:</label>
				<input id="email" name="email" type="text" size="25" />
				<input id="addBuddyButton" type="submit" value="Search" />
			</fieldset>
			<div id="searchResults"></div>
		</div>
		<div id="removeBuddyFrm" class="yui-g">
			<div id="activeBuddyList"></div>
		</div>
	</div>
	<br />
     <div id="ft"><?php include 'footer.html'; ?></div>
</div>
</body>
</html>
