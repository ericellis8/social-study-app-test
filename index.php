<?php
mysql_connect("localhost","mia","soulskater") or die(mysql_error());
mysql_select_db("social_study_groups") or die(mysql_error());
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
	require dirname(__FILE__)."/phpFreeChat/src/phpfreechat.class.php";
	$params = array();
	$params["title"] = "ClassPoint";
	$params["nick"] = $_SESSION['username'];	
	$myNickName = $params["nick"];
	$params['firstisadmin'] = true;
	$params["isadmin"] = true; // makes everybody admin: do not use it on production servers ;)
	$params["serverid"] = md5("chat"); //__FILE__); // calculate a unique id for this chat
	$params["debug"] = false;
	$params["theme_path"] = "/phpFreeChat/themes";
	$params["theme"] = "default";
	$params["showsmileys"] = false;
	$params["shownotice"] = 0;
	$params["clock"] = false;
	$params["quit_on_closedwindow"] = true;
	$chat = new phpFreeChat( $params );
}

?> 

 <!DOCTYPE html>  
    <html lang="en">  
        <head>  
            <meta charset="utf-8">  
            <title>Pup 'n Suds</title>  
            <link rel="stylesheet" href="nav.css">  
             <link rel="stylesheet" title="classic" type="text/css" href="style/generic.css" />
  <link rel="stylesheet" title="classic" type="text/css" href="style/header.css" />
  <link rel="stylesheet" title="classic" type="text/css" href="style/footer.css" />
  <link rel="stylesheet" title="classic" type="text/css" href="style/menu.css" />
  <link rel="stylesheet" title="classic" type="text/css" href="style/content.css" />  

            <!--[if IE]>  
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>  
            <![endif]--> 

<!--
<script type="text/javascript">
function myPopup() {
window.open( "newStudySession.php", "New Study Session", "status = 1, height = 700, width = 700, resizable = 0" )
}
</script>
-->

<script type="text/javascript">
function logOut(){
	pfc.sendRequest('/quit');
	window.location = "logout.php";
}

function getUser(){
	var li = pfc.buildNickItem('904cb17acbe02c2ad452f5be0901e317d031502f');
	document.getElementById('buddyList').appendChild(li);
}
function changeName(nick){
	pfc.sendRequest('/nick ' + nick);
}
setTimeout("changeName('<?php echo $username; ?>')", 1000);
</script>

<script>
function PopupCenter(pageURL, title,w,h) {
var left = (screen.width/2)-(w/2);
var top = (screen.height/2)-(h/2);
var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 
</script>
<script type="text/javascript">
function loadStudyRooms(username)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("studyRoom").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","studyRooms.php?username="+username,true);
xmlhttp.send();
}
function getBuddyList(username)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("buddyList").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","buddyList.php?username="+username,true);
xmlhttp.send();
}
</script>
 
        </head>  
        <body class="no-js">  
            <nav id="topNav">  
                    <ul>  
                        <li><a href="javascript:void(0);" onclick="PopupCenter('newStudySession.php', 'New Study Session', 700, 700);" title="Nav Link 1">Start A Study Session</a></li>  
                    <li>  
                        <a href="#" title="Nav Link 1">Nav Link 2  
                        <ul>  
                            <li><a href="#" title="Sub Nav Link 1">Sub Nav Link 1</a></li>  
                            <li><a href="#" title="Sub Nav Link 2">Sub Nav Link 2</a></li>  
                            <li><a href="#" title="Sub Nav Link 3">Sub Nav Link 3</a></li>  
                            <li><a href="#" title="Sub Nav Link 4">Sub Nav Link 4</a></li>  
                            <li class="last"><a href="#" title="Sub Nav Link 5">Sub Nav Link 5</a></li>  
                        </ul>  
                    </li>  
                    <li><a href="#" title="Nav Link 1">Nav Link 3</a></li>  
                    <li><a href="#" title="Nav Link 1">Nav Link 4</a></li>  
                    <li><a href="#" title="Nav Link 1" onclick="return popitup('newStudySession.html')">Create A New Study Group</a></li>  
                    <?php
                    	if($_SESSION['loggedIn'] == 'true'){
                    		echo "<li><a onclick='logOut()' href='javascript:void(0)'>Log Out</a></li>";
                    		echo "<span style='color:#eee;position:relative;top:10px;left:15px;'>Welcome " . $_SESSION['name'] . "</span>";
                    	}
                    ?>
                </ul>  
            </nav>  
            <script src="js/jquery.js"></script>  
            <script src="js/modernizr.js"></script> 
	
	<table height="100%" width='100%' style="background:#FFF;"><!---image:url('./phpFreeChat/themes/default/images/background.gif');">-->
		<tr height="300px">
			<td width="20%" align="left" valign="top" style="border: 3Px solid black;">	
<?php
	if($_SESSION['loggedIn'] != 'true'){
?>
			Log in
			<table>
				<tr><td>
					<form align="left" action="login.php" method="post">
						Username: 
				</td><td>
						<input type="text" name="username" /><br>
				</td></tr>
				<tr><td>
						Password: 
				</td><td>	
						<input type="password" name="password" /><br>
				</td></tr>
			</table>
					<center>
						<input type="submit" value="Sign in" />
					</center>
			<br>
					</form>
			login with username = testUser <BR>
			and password = password <BR>
			or create your own in the DB
<?php
	}else{
?>
		<div id="studyRoom">
		
		</div>
		<script type="text/javascript">
			loadStudyRooms('<?php echo $username; ?>');
			setInterval("loadStudyRooms('<?php echo $username; ?>')", 5000);
		</script>
		<!-- <td width="250px"align="center" valign="top" style="border: 3Px solid black;"> -->
			<?php			
				if($_SESSION['loggedIn'] == 'true'){
				//require_once(dirname(__FILE__)."/buddyList.php");
				?>
				<BR><BR>
				<div id="buddyList">
					<script type="text/javascript">
						//setInterval("getUser()",1000);
						//setTimeout("getUser()",500);
						getBuddyList('<?php echo $username; ?>');
						setInterval("getBuddyList('<?php echo $username; ?>')", 5000);
						//getUser();
					</script>
					
					
				</div>
				
				<?php
				}	
  				
			?>
			<!--</td>-->

<?php
		//require_once(dirname(__FILE__)."/studyRooms.php");
	}

?>
			</td>
			<td style="border: 3Px solid black;" valign="top" width="40%">
			<iframe style="padding-left: 0px"  frameborder="0" scrolling="no" src="../../root/uniondraw/uniondraw.html" width="100%" height="335" width="600"></iframe>
			<iframe style="padding-left: 0px"  frameborder="0" scrolling="no" src="../../root/uniondraw/uniondraw.html" width="100%" height="335" width="600"></iframe>
			</td>
			<td width="100%" align="center" valign="top" style="border: 3Px solid black;">
					<div class="content">
						<?php
							if($_SESSION['loggedIn'] == 'true')
							{
								$chat->printChat(); 
								if (isset($params["isadmin"]) && $params["isadmin"]) { 
									?><!--<p style="color:red;font-weight:bold;">Warning: because of "isadmin" parameter, everybody is admin. Please modify this script before using it on production servers !</p>	--><?php
							   	}
						?>	   
					</div>	
						<?php
							}
						?>
						
			</td>
			
		</tr>
	</table>
	</body>  
</html>  
