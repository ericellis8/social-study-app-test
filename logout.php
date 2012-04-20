<?php
mysql_connect("localhost","mia","soulskater") or die(mysql_error());
mysql_select_db("social_study_groups") or die(mysql_error());
session_start();
//require_once(dirname(__FILE__)."/phpFreeChat/src/pfccommand.class.php");
	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['major']);
	unset($_SESSION['loggedIn']);
//	$u  =& pfcUserConfig::Instance();
 //  $ct =& pfcContainer::Instance();
 //  $ct->removeNick(NULL, $u->nickid);
header("Location: index.php");
exit;
?>