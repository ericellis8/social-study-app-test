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

$error='Your profile successully updated.';
//Clean vars
require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
$purifier = new HTMLPurifier($config);
$clnUsername =  $purifier->purify($_POST['profUserName']);
$clnUserId =  intval($purifier->purify($_POST['profUserId']));
$clnFullName =  $purifier->purify($_POST['profFullName']);
$clnEmail =  $purifier->purify($_POST['profEmail']);
$clnTimeOffset =  $purifier->purify($_POST['profTimeZoneOffset']);
$clnTimeOffset = is_numeric($clnTimeOffset) ? $clnTimeOffset : -8;
$clnverifyPassword =  $purifier->purify($_POST['verifyPassword']);

//Additional validation checks
if (empty($clnUserId) || empty($clnFullName) || empty($clnUsername) || empty($clnEmail) || empty($clnverifyPassword)) {
	$error="All fields are required.  Please try again.";
} else if (strlen($clnEmail)>100) {
    $error="You email address must be less than 100 characters!";
} else if (strlen($clnFullName)>100) {
    $error="You name must be less than 100 characters!";
} else if (abs($clnTimeOffset)>12) {
    $error="Time offset must be between -12 and 12";    
} else {
    if ($clnUserId !== $mia->getCrtUserID()) {
        $error = "bad bad bad...! :)";	
    } else if ($mia->checkUser($clnUserId, $clnUsername, $clnverifyPassword) === false) {
        //check if the given password matches the users pass.
        $error = "Wrong credentials.";
    } else if ($mia->updateUserProfile($clnUserId, $clnFullName, $clnEmail, $clnTimeOffset) === false) {
        $error = "Profile update failed.";
    }
}
echo $error;
?>
