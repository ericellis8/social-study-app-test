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

//Clean post vars
if (isset($_POST['showoffline'])) {
	$showoffline = intval($_POST['showoffline']);
} else {
	$showoffline = 1;
}

$_SESSION['showoffline'] = $showoffline;
?>