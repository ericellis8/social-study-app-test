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
    header('Location: index.php');
}

//Process the new message
require('includes/utility_functions.php');
$uncleanMessage = $_POST['message'];

//Clean post vars
require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
$purifier = new HTMLPurifier($config);
$clnUsernameTo = $purifier->purify($_POST['userto']);
$clnMessage = $purifier->purify($uncleanMessage);
$clnMessage = swapEmoticon($clnMessage); //Convert emoticon shorthands to images
$clnMessage = swapHyperlink($clnMessage); //Convert link look-a-likes to real links
$rand = sha1(mt_rand());

$newMessage = $mia->addMessage($clnUsernameTo, $clnMessage, $rand);
if ($newMessage) {
	echo stripslashes($clnMessage);
}
?>