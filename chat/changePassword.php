<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start(); 

$resetEmail = false;
if (isset($_GET['user']) && isset($_GET['email']) && isset($_GET['activation_code'])) {
    //This appears to be coming from the normal reset email (need all 3 so ignore otherwise)
    $errorMessage = '';
    if (isset($_SESSION["loginError"]) && !empty($_SESSION["loginError"])) {
    	$errorMessage = $_SESSION["loginError"];
    	$_SESSION["loginError"]='';  //clear the error so as not show again unless there is a new error
    }

    //Clean GET vars
    require('includes/htmlpurifier/library/HTMLPurifier.auto.php');
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
    $purifier = new HTMLPurifier($config);
    $clnUsername = $purifier->purify($_GET['user']); 
    $clnEmail = $purifier->purify($_GET['email']); 
    $clnActivationCode = $purifier->purify($_GET['activation_code']);
    $resetEmail = true;
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<link rel="stylesheet" href="css/reset-fonts-grids.css" type="text/css" />
	<link rel="stylesheet" href="css/mia.css" type="text/css" media="screen" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Mia-Chat Change Password</title>
</head>
<body>
<div id="doc3" class="yui-t7">
	<div id="hd"><img src="images/mia_logo.png" alt="Mia-Chat Logo" /></div>
	<div id="bd">
	    <?php
	    if ($resetEmail===false) {
	        ?>
	        <div id="login" class="login yui-g">
	            <p class="errorMessage">Invalid request!</p>
	        </div>
	        <?php
	    } else {
	        ?>
	        <div id="login" class="login yui-g">
    			<h1>Mia-Chat: Change Password</h1>
    			<form id="chgPassFrm" method="post" action="doChangePassword.php">
    			<fieldset>
    			    <label for="username">Username:</label>
    				<input id="username" name="username" type="text" size="25" value="<?php echo $clnUsername; ?>" readonly />
    				<label for="email">Email Address:</label>
    				<input id="email" name="email" type="text" size="25" value="<?php echo $clnEmail; ?>" readonly />
    				<label for="newPassword">New Password:</label>
    				<input id="newPassword" name="newPassword" type="password" size="25" />
    				<label for="verifyPassword">Verify Password:</label>
    				<input id="verifyPassword" name="verifyPassword" type="password" size="25" />
    				<input class="restButton" type="submit" value="Change Password" />
    				<input type="hidden" id="activationCode" name="activationCode" value="<?php echo $clnActivationCode; ?>" />
    			</fieldset>
    			</form>
    			<?php
    			if ($errorMessage) {
    				echo "<div class=\"errorMessage\">$errorMessage</div>";
    			}
    			?>
    		</div>
	        <?php
	    }
	    ?>
	</div>
</div>
<script type="text/javascript" src="includes/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="includes/js/jquery/plugins/validate/lib/jquery.metadata.js"></script>
<script type="text/javascript" src="includes/js/jquery/plugins/validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="includes/js/mia/miaChangePassword.js"></script>
</body>
</html>
