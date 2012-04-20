<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start(); 
include('includes/utility_functions.php');
installationCheck();  //Do secure installation check

$errorMessage = '';
if (isset($_SESSION["loginError"]) && !empty($_SESSION["loginError"])) {
	$errorMessage = $_SESSION["loginError"];
	$_SESSION["loginError"]='';  //clear the error so as not show again unless there is a new error
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	<link rel="stylesheet" href="css/reset-fonts-grids.css" type="text/css" />
	<link rel="stylesheet" href="css/mia.css" type="text/css" media="screen" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>   
	<title>Mia-Chat Login Page</title>
</head>
<body>
<div id="doc3" class="yui-t7">
	<div id="hd"><img src="images/mia_logo.png" alt="Mia-Chat Logo" /></div>
	<div id="bd">
	    <noscript>  
            <p>Mia-Chat requires JavaScript to function. Please turn it on and refresh this page.</p>
        </noscript>
		<div id="login" class="login yui-g">
			<h1>Mia-Chat: Login</h1>
			<form id="loginFrm" method="post" action="doLogin.php">
			    <fieldset>
    				<label for="username">Username:</label>
    				<input id="username" name="username" type="text" size="25" />
    				<label for="password">Password:</label>
    				<input id="password" name="password" type="password" size="25" />
    				<input class="loginButton" type="submit" value="Login" />
    				<p>New user? <a id="newUser" href="#registration">Register here</a></p>
    				<p>Forgot password? <a id="reset" href="#passwordreset">Reset here</a></p>
    				<?php
        			if ($errorMessage) {
        				echo '<p class="errorMessage">' . $errorMessage . '</p>';
        			}
        			?>
    			</fieldset>
			</form>
		</div>
		<div id="registration" class="login hideme yui-g">
			<h1>Mia-Chat: Registration</h1>
			<form id="regFrm" method="post" action="doRegistration.php">
			<fieldset>
				<label for="regUsername">Username:</label>
				<input id="regUsername" name="regUsername" type="text" size="25" maxlength="50" />
                 <label for="regFullname">Full Name:</label>
				<input id="regFullname" name="regFullname" type="text" size="25" maxlength="100" />
				<label for="regEmail">Email Address:</label>
				<input id="regEmail" name="regEmail" type="text" size="25" maxlength="100" />
                <label for="regTimeZoneOffset">Time Zone:</label>
                <select name="regTimeZoneOffset" id="regTimeZoneOffset">
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
				<label for="regPassword">Password:</label>
				<input id="regPassword" name="regPassword" type="password" size="25" />
				<label for="verifyPassword">Verify Password:</label>
				<input id="verifyPassword" name="verifyPassword" type="password" size="25" />
				<?php 
				if (doCaptcha()!==false) {
				    ?>
				    <label for="spamcode">Enter text from image below:</label>
        			<input id="spamcode" name="spamcode" type="text" size="25" />
				    <img id="spamimage" src="getCaptchaImage.php" alt="CAPTCHA image sould be here" /><br />
                     Cannot read this, <a id="rerequest-captcha" href="#">please show another</a><br/>
				<?php
				}
				?>
                 <label for="regTermsConditions">Terms and Conditions</label>
                 <textarea id="regTermsConditions" cols="40" rows="8" readonly="readonly"><?php
                    $handle = fopen("./termsandconditions.txt.php", "r") or die("Can't open Terms and Conditions file");
                    $terms_and_conditions = "";
                    while (!feof($handle)) {
                        $terms_and_conditions .= fread($handle, 8192);
                    }
                    fclose($handle);

                    echo $terms_and_conditions;
                 ?></textarea>
				<label for="regAcceptTermsConditions">Please check to accept the Terms &amp; Conditions</label>
                <input type="checkbox" name="regAcceptTermsConditions" id="regAcceptTermsConditions"/>
                     
                <p><input class="regButton" type="submit" value="Register" /></p>
				<p>Return to <a id="login-user-reg" class="loginUser" href="#login">Login</a></p>
			</fieldset>
			</form>
		</div>
		<div id="passwordReset" class="login hideme yui-g">
			<h1>Mia-Chat: Password Reset</h1>
			<p>Enter your username and password below.  An email will be sent to start the reset process.</p>
			<form id="resetFrm" method="post" action="doPasswordReset.php">
			<fieldset>
				<label for="resetUsername">Username:</label>
				<input id="resetUsername" name="resetUsername" type="text" size="25" maxlength="50" />
				<label for="resetEmail">Email Address:</label>
				<input id="resetEmail" name="resetEmail" type="text" size="25" maxlength="100" />
				<input class="resetButton" type="submit" value="Send Password" />
				<p>Return to <a id="login-user-reset" class="loginUser" href="#login">Login</a></p>
			</fieldset>
			</form>
		</div>
	</div>
     <div id="ft"><?php include 'footer.html'; ?></div>
</div>
<script type="text/javascript" src="includes/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="includes/js/jquery/plugins/validate/lib/jquery.metadata.js"></script>
<script type="text/javascript" src="includes/js/jquery/plugins/validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="includes/js/mia/miaLogin.js"></script>
</body>
</html>