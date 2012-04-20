<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start();
require('includes/mia.classes.php');
$mia = MiaChatDb::getInstance();

$error='';
if (isset($_POST['resetUsername']) && isset($_POST['resetEmail'])) {
    //Clean vars
    require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
    $purifier = new HTMLPurifier($config);
    $clnUsername =  trim($purifier->purify($_POST['resetUsername']));
    $clnEmail =  trim($purifier->purify($_POST['resetEmail']));
    
    if ($mia->passwordReset($clnUsername, $clnEmail)===false) {
        $error = 'Unable to reset password.  Please contact your administrator or try again.';
    } else {
        //Note: this wasn't an error, but we'll use the dialog box to display the success message
        $error = 'Password reset started sucessfully.  Check your email!';
    }
} else {
    //It doesn't appear to have come from the email or the form
    $error = 'Unable to reset password.  Please contact your administrator or try again.';
}

//There is always a message in this case so send them back and display it
$_SESSION["loginError"]=$error;
header('Location: index.php'); //Send them back to the login page

?>