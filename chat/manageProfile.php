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
	<title>Mia-Chat Manage Profile</title>
	<link rel="stylesheet" href="css/reset-fonts-grids.css" type="text/css" />
	<link rel="stylesheet" href="css/mia.css" type="text/css" media="screen" />
	<script type="text/javascript" src="includes/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="includes/js/mia/manageUserProfile.js"></script>
</head>
<body>
<div id="doc3" class="yui-t7">
	<div id="hd"><h1>Mia-Chat Manage Profile</h1></div>
	<div id="bd">
		<div id="manageProfileFrm" class="yui-g">
			<h2>Update your profile</h2>
			
			<fieldset>
				<label for="profUsername">Username:</label>
				<span id="profUserName"><?php echo $_SESSION['username'];?></span>
                <label for="profFullname">Full Name:</label>
				<input id="profFullname" name="regFullname" type="text" size="25" value="" />
				<label for="profEmail">Email Address:</label>
				<input id="profEmail" name="regEmail" type="text" size="25" />
				<label for="verifyPassword">Verify Password:</label>
				<input id="verifyPassword" name="verifyPassword" type="password" size="25" />
                <input id="profUserid" name="profUserid" type="hidden"  value="<?php echo $_SESSION['userid'];?>" />
				<input class="updateProfileButton" type="button" value="Update Profile" />				
			</fieldset> 
            <div id="profUpdateProcess" class="hideme">Updating profile</div>
        </div>
	</div>
</div>
</body>
</html>
