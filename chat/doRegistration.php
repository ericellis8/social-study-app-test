<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start();
require('includes/mia.classes.php');
$mia = MiaChatDb::getInstance();
include('includes/utility_functions.php');

//Clean post vars
require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
$purifier = new HTMLPurifier($config);
$clnFullName =  $purifier->purify($_POST['regFullname']);
$clnUsername =  $purifier->purify($_POST['regUsername']);
$clnEmail =  $purifier->purify($_POST['regEmail']);
$clnPassword =  $purifier->purify($_POST['regPassword']);
$clnTimeOffset =  $purifier->purify($_POST['regTimeZoneOffset']);
$clnTimeOffset = is_numeric($clnTimeOffset) ? $clnTimeOffset : -8;
$clnVerifyPassword =  $purifier->purify($_POST['verifyPassword']);
$error='';

//Are we to do a captcha check?  Check this before wasting time on the rest
$cpatchaCheck = doCaptcha(); 
if ($cpatchaCheck!==false) {
    include('includes/php5captcha/Captcha.php');
    $options['sessionName'] = 'miahash';
    $options['secretKey'] = $cpatchaCheck;
    $captcha = new Captcha($options);
    if ($captcha->isKeyRight($_POST['spamcode'])===false) {
        $error="The value you entered did not match the image!";
        $_SESSION["loginError"]=$error;
    	header('Location: index.php'); //Send them back to the login page
    	exit;
    }
}

//Additional validation checks
if (empty($clnFullName) || empty($clnUsername) || empty($clnEmail) || empty($clnPassword) || empty($clnVerifyPassword)) {
	$error="All fields are required.  Please try again.";
} else if (strlen($clnUsername)<5) {
	$error="Your username must consist of at least 5 characters!";
} else if (strlen($clnPassword)<6) {
	$error="Your password must be at least 6 characters long!";
} else if ($clnPassword!=$clnVerifyPassword) {
    $error="The passwords do not match!";
} else if (strpos($clnUsername, ' ') || strpos($clnUsername, ':')) {
	$error="Usernames may not contain spaces or the ':' character!";
} else if (strlen($clnUsername)>50) {
    $error="You username must be less than 50 characters!";
} else if (strlen($clnEmail)>100) {
    $error="You email address must be less than 100 characters!";
} else if (strlen($clnFullName)>100) {
    $error="You name must be less than 100 characters!";
} else if (abs($clnTimeOffset)>12) {
    $error="Time offset must be between -12 and 12";
} else {
	//check for unique username
	if ($mia->uniqueUsernameCheck($clnUsername)===false) {
		$error="This username already exists.  Please try again!";
	}
}

if ($error!=='') {
	$_SESSION["loginError"]=$error;
	header('Location: index.php'); //Send them back to the login page
	exit;
} else {
	//Create the account
	if ($mia->createUserAccount($clnFullName, $clnUsername, $clnEmail, $clnPassword, $clnTimeOffset)!==false) {
	    $error="Account successfully created.  Please login.";
	    $_SESSION["loginError"]=$error;
		header('Location: index.php'); //Success, send them to the login
		exit;
	} else {
		$error="Unable to create this new account.  Please try again!";
		$_SESSION["loginError"]=$error;
		header('Location: index.php'); //Send them back to the registration page
		exit;
	}
}

?>