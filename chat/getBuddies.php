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
    die('Invalid session, operation not permitted! Please <a href="index.php">login</a>.');
}

//Does the user prefer to see offline buddies?
if (isset($_SESSION['showoffline'])) {
	$showoffline = intval($_SESSION['showoffline']);
} else {
	$showoffline = 1; //yes
}

$buddies = $mia->getBuddies($showoffline);
if ($buddies) {	
	require('includes/json.php');
	$json = new Services_JSON();
	$rBuddies = $json->encode($buddies);
	echo $rBuddies;	
}
?>