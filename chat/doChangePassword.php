<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start();

//Clean post vars
require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
$purifier = new HTMLPurifier($config);
$clnUsername =  $purifier->purify($_POST['username']);
$clnEmail =  $purifier->purify($_POST['email']);
$clnNewPassword =  $purifier->purify($_POST['newPassword']);
$clnVerifyPassword =  $purifier->purify($_POST['verifyPassword']);
$clnActivationCode =  $purifier->purify($_POST['activationCode']);

$error='';
if (empty($clnUsername) || empty($clnEmail) || empty($clnNewPassword) || empty($clnVerifyPassword) || empty($clnActivationCode)) {
	$error="All fields are required.  Please try again.";
} else if (strlen($clnNewPassword)<6) {
    $error="Your password must be at least 6 characters long!";
} else if ($clnNewPassword!=$clnVerifyPassword) {
    $error="The passwords do not match!";
}

if ($error!=='') {
	$_SESSION["loginError"]=$error;
	header('Location: changePassword.php?user='.$clnUsername.'&email='.$clnEmail.'&activation_code='.$clnActivationCode); //Send them back
	exit;
} else {
	//Update password
	require('includes/mia.classes.php');
    $mia = MiaChatDb::getInstance();
	if ($mia->updatePassword($clnUsername, $clnEmail, $clnNewPassword, $clnActivationCode)!==false) {
	    $error = "Successfully changed password!  Please login."; //Not really an error, but we'll reuse the message box
		$_SESSION["loginError"]=$error;
		header('Location: index.php'); //Success, send them to the login
		exit;
	} else {
		$error = "Unable to change password!";
		$_SESSION["loginError"]=$error;
		header('Location: changePassword.php?user='.$clnUsername.'&email='.$clnEmail.'&activation_code='.$clnActivationCode); //Send them back
		exit;
	}
}

?>