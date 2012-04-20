<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
session_start();
require('includes/utility_functions.php');
installationCheck();  //Do secure installation check

require('includes/mia.classes.php');
$mia = MiaChatDb::getInstance();
if ($mia->sessionHijackCheck()===false) {
    header('Location: index.php');
}

//Does the user prefer to see offline buddies?
if (isset($_SESSION['showoffline'])) {
    $showoffline = intval($_SESSION['showoffline']);
} else {
    $showoffline = 1; //yes
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Mia-Chat</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="css/reset-fonts-grids.css" type="text/css" />
    <link rel="stylesheet" href="css/mia.css" type="text/css" media="screen" />
    <link type="text/css" href="includes/js/jquery/ui/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
    <link href="includes/js/greybox/greybox.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
    <div id="doc3" class="yui-t6">
        <div id="hd" role="banner">
            <img src="images/mia_logo.png" alt="Mia Logo" /> 
        </div>
        <div id="bd" role="application">
        	<div class="yui-g">
        	    <div id="chat-splitter">

                	<div id="left-pane">
                	    <noscript>  
                            <p>Mia requires JavaScript to function. Please turn it on and refresh this page.</p>
                        </noscript>             
                        <div id="tabs">
                        	<ul></ul>
                        </div>
                            
                        <form action="main.php" method="get">
                            <p><input type="hidden" id="activeChat" value="" /></p>
                            <p><input type="hidden" id="welcomeMessage" value="<?php echo stripslashes(getWelcomeMessage()); ?>" /></p>
                        </form>
                	</div>

                	<div id="right-pane">
                		<div id="right-pane-wrapper">
                            <div id="rightnav-top">
                                <span id="manage-profile"><a href="manageUserProfile.php" class="greybox">My profile</a></span>
                                <span id="nav-logout"><a href="doLogout.php" title="logout">Logout</a></span>
                                <br/>
                                <span id="who-am-i">Welcome <?php echo $_SESSION['fullname']; ?></span> 
                            </div>	
                            <form id="userstatus" action="main.php" method="post">
                                <p>
                                    <label id="stat">Your Status:</label>
                                    <select id="uStatus" name="uStatus">
                                        <option value="online">Available</option>
                                        <option value="busy">Busy</option>
                                        <option value="away">Away</option>
                                    </select>
                                </p>
                            </form>
                            <div id="userPreferences">
                                <h2>Preferences</h2>
                                <form id="frmprefs" action="main.php" method="post">
                                    <p>
                                    <label>Show Offline Buddies: </label><br />
                                        <input type="radio" id="showofflineYes" class="showoffline" name="showoffline" value="1" <?php if ($showoffline===1) echo 'checked="checked"';?> />Yes
                                               <input type="radio" id="showofflineNo" class="showoffline" name="showoffline" value="0" <?php if ($showoffline===0) echo 'checked="checked"';?> /> No
                                           </p>
                                </form>
                            </div>
                            <h2>
                                Buddy List 
                                <img id="preferences" src="images/tango/preferences.png" alt="Preferences button" />
                            </h2>
                            <div id="nav">
                                <ul id="buddylist">
                                    <li class="filler">No buddies</li>
                                </ul>
                            </div>
                            <div id="navFooter">
                                <p id="manageBuddylist"><a href="manageBuddy.php" title="Manage Buddies" class="greybox">Manage Buddies?</a></p>
                            </div>
                        </div>
                	</div>

                </div>
                
            </div>
        </div>
        <div id="ft" role="contentinfo">
            <p><?php include 'footer.html'; ?></p>
        </div>
    </div>
    
    <script type="text/javascript" src="includes/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="includes/js/jquery/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="includes/js/jquery/plugins/timer/jquery.timer.js"></script>
    <script type="text/javascript" src="includes/js/jquery/plugins/cookie/jquery.cookie.js"></script>
    <script type="text/javascript" src="includes/js/jquery/plugins/splitter/splitter.js"></script>
    <script type="text/javascript">var GB_ANIMATION = true;</script>
    <script type="text/javascript" src="includes/js/greybox/greybox.js"></script>
    
    <script type="text/javascript" src="includes/js/mia/chatscroll.js"></script>
    <script type="text/javascript" src="includes/js/mia/date.format.js"></script>
    <script type="text/javascript" src="includes/js/mia/mia.js"></script>
</body>
</html>