<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

/**
* Recursive stripslashes function for arrays
**/
function stripslashes_deep($value) {
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}

/**
* Replace link look-a-likes in user messages with actual links
* @param message
**/
function swapHyperlink($message) {
    return preg_replace("/((\S)+:\/\/(\S)+)/i",
							"<a href=\"$1\">$1</a>", $message);
}

/**
* Replace emoticon shorthand wth the actual image
* @param message
*/
function swapEmoticon($message) {
	/*
	;) = face-wink.png
	8) = face-glasses.png
	0-] = face-angel.png
	8-( = face-crying.png
	!) = face-devilish.png
	:( = face-sad.png
	:-) = face-grin.png
	:) = face-smile.png
	0-) = face-surprise.png
	*/
	$emotArray = array(';)'=>'face-wink', '8)'=>'face-glasses', '0-]'=>'face-angel', '8-('=>'face-crying', 
				'!)'=>'face-devilish', ':('=>'face-sad', ':-)'=>'face-grin', ':)'=>'face-smile', '0-)'=>'face-surprise');

	foreach($emotArray as $key=>$value) {
		$message=str_replace($key, '<img src="images/tango/smiles/'.$value.'.png" />', $message);
	}
	return $message;
}

function doCaptcha() {
    // Parse the Mia-Chat config file
	$ini_array = parse_ini_file("config.ini.php", true);
	$captchaSetting = intval($ini_array['global_info']['enable_captcha']);
	$secret = $ini_array['global_info']['secret'];
	
    if ($captchaSetting==0 || !function_exists("gd_info")) {
        return false;
    } else {
        return $secret; //saves parsing it again once we know
    }
}

function getServerTimeOffset() {
    // Parse the Mia-Chat config file
	$ini_array = parse_ini_file("config.ini.php", true);
	$server_time_offset = floatval($ini_array['global_info']['server_time_offset']);
	//default to PST for no good reason ;)
    return ($server_time_offset == null) ? -8 : $server_time_offset;
}

function getWelcomeMessage() {
    // Parse the Mia-Chat config file
	$ini_array = parse_ini_file("config.ini.php", true);
	$custom_welcome_message = $ini_array['global_info']['custom_welcome_message'];
    return $custom_welcome_message;
}

//Sanity check for a secure installation
function installationCheck() {
    //file path is relative to the index.php that includes this
    if (@file_exists('config.ini.php')===false && @is_dir('installation')) {
        header("Location: installation/index.php"); //Do new install
    } else if (@file_exists('config.ini.php')===true && @is_dir('installation')) {
        die('ERROR: Installation not complete.  Please remove the "installation" directory and refresh. You can also visit us at http://brilaps.com and request support in our support forum.');
    } else if (!is_writable('includes/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer.php')) {
        $message = 'ERROR: Installation not complete.  Please review the HTMLPurifier section of the manual installation instructions found in';
        $message .= ' the "/installation/manual_install/INSTALL.txt" file for assistance with proper server permission configurations.';
        $message .= ' You can also visit us at http://brilaps.com and request support in our support forum.';
        die($message);
    }
}

?>
