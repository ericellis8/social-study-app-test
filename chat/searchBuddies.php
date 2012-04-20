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

require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
$purifier = new HTMLPurifier($config);

$clnFullname =  $purifier->purify($_GET['fullname']);
$clnUsername =  $purifier->purify($_GET['username']);
$clnEmail =  $purifier->purify($_GET['email']);

$buddies = $mia->searchBuddies($clnFullname, $clnUsername, $clnEmail);
if ($buddies) {
	require('includes/json.php');
	$json = new Services_JSON();
	$rBuddies = $json->encode($buddies);
	echo $rBuddies;	
} else {
	echo 0;
}
?>