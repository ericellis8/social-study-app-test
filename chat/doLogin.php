<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start();
require('includes/mia.classes.php');
$mia = MiaChatDb::getInstance();

//Clean post vars
require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
$purifier = new HTMLPurifier($config);
$clnUsername =  $purifier->purify($_POST['username']);
$clnPassword =  $purifier->purify($_POST['password']);

$error='Invalid username or password.  Please try again.';
if (empty($clnUsername) || empty($clnPassword)) {
	$_SESSION["loginError"]=$error;
	header('Location: index.php'); //Send them back to the login page
	exit;
} else {
	$result = $mia->userLogin($clnUsername, $clnPassword);
	
	//$result = $mia->userLogin($clnUsername, $clnPassword);
	if ($result===false) {	
		$_SESSION["loginError"]=$error;
		header('Location: index.php'); //Send them back to the login page
		exit;
	} else {
		header('Location: main.php'); //Valid user, redirect to main page
	}
}
?>