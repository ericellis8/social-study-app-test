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

include('includes/utility_functions.php');

//Grab pending message for the user
$messages = $mia->getMessages();
if ($messages) {
	require('includes/json.php');
	$json = new Services_JSON();
	
	//Process the message for end-user display
    //javascript date format var sentdatetime= dateFormat(now, "dddd, mmm d, h:MM TT");
	$messages = stripslashes_deep($messages);
	foreach($messages as $key=>$value) {
		$to_timeoffset = floatval($value['to_timeoffset']);
        foreach($value as $k=>$v) {
			if ($k=='sent_date_time') {
                $adjusted_time = strtotime($v) - (floatval(getServerTimeOffset() - $to_timeoffset) * 3600);
                $messages[$key]['sent_date_time'] =  date("l, M j, g:i A", $adjusted_time);
			}
		}
	}
		
	$rMessages = $json->encode($messages);
	echo $rMessages;	
}
?>