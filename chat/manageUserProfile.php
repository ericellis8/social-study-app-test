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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Mia-Chat Manage Profile</title>
    <link rel="stylesheet" href="css/reset-fonts-grids.css" type="text/css" />
    <link rel="stylesheet" href="css/mia.css" type="text/css" media="screen" />
    <script type="text/javascript" src="includes/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="includes/js/mia/manageUserProfile.js"></script>
</head>
<body>
<div id="doc3" class="yui-t7">
<div id="hd"><h1>Mia-Chat Manage Profile</h1></div>
<div id="bd">
<div id="manageProfileFrm" class="yui-g">
<h2>Update your profile</h2>
<form id="updateProfileFrm">
<fieldset>
    <label for="profUserNameLabel">Username:</label>
    <span id="profUserNameLabel"><?php echo $_SESSION['username'];?></span>
    <label for="profFullName">Full Name:</label>
    <input id="profFullName" name="profFullName" type="text" size="25" maxlength="100" value="" />
    <label for="profEmail">Email Address:</label>
    <input id="profEmail" name="profEmail" type="text" size="25" maxlength="100" />
    
    <label for="profTimeZoneOffset">Time Zone:</label>
    <select name="profTimeZoneOffset" id="profTimeZoneOffset">
        <option value="-12">(GMT -12:00) Eniwetok, Kwajalein</option>
        <option value="-11">(GMT -11:00) Midway Island, Samoa</option>
        <option value="-10">(GMT -10:00) Hawaii</option>
        <option value="-9">(GMT -9:00) Alaska</option>
        <option value="-8" selected="selected">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
        <option value="-7">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
        <option value="-6">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
        <option value="-5">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
        <option value="-4">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
        <option value="-3.5">(GMT -3:30) Newfoundland</option>
        <option value="-3">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
        <option value="-2">(GMT -2:00) Mid-Atlantic</option>
        <option value="-1">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
        <option value="0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
        <option value="1">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
        <option value="2">(GMT +2:00) Kaliningrad, South Africa</option>
        <option value="3">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
        <option value="3.5">(GMT +3:30) Tehran</option>
        <option value="4">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
        <option value="4.5">(GMT +4:30) Kabul</option>
        <option value="5">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
        <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
        <option value="6">(GMT +6:00) Almaty, Dhaka, Colombo</option>
        <option value="7">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
        <option value="8">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
        <option value="9">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
        <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
        <option value="10">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
        <option value="11">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
        <option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
    </select>
    
    <label for="verifyPassword">Verify Password:</label>
    <input id="verifyPassword" name="verifyPassword" type="password" size="25" />
    <input id="profUserId" name="profUserId" type="hidden"  value="<?php echo $_SESSION['userid'];?>" />
    <input id="profUserName" name="profUserName" type="hidden"  value="<?php echo $_SESSION['username'];?>" />
    <input class="updateProfileButton" type="submit" value="Update Profile" />				
</fieldset> 
</form>
<div id="profUpdateProcess" class="hideme">Updating profile</div>
</div>
</div>
<br />
<div id="ft"><?php include 'footer.html'; ?></div>
</div>
<script type="text/javascript" src="includes/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="includes/js/jquery/plugins/validate/lib/jquery.metadata.js"></script>
<script type="text/javascript" src="includes/js/jquery/plugins/validate/jquery.validate.min.js"></script>
</body>
</html>
