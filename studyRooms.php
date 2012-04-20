<center><u style='font:15px Tahoma, Sans-serif;'>
			Study Rooms</u></center>
<p align="left">	
<?php
mysql_connect("localhost","mia","soulskater") or die(mysql_error());
mysql_select_db("social_study_groups") or die(mysql_error());
session_start();


	function getNumberOfUsers($channel) 
	{
		$serverid = isset($GLOBALS['serverid']) ? $GLOBALS['serverid'] : 0;
		require_once dirname(__FILE__)."/phpFreeChat/src/pfcinfo.class.php";
		$info  = new pfcInfo( md5("pupchat") );
		$listOnline = $info->getOnlineNick($channel, 20);
		return count($listOnline);
	}

	$username = $_GET['username'];
	$query = "Select class_name, major from classes, class_permissions, user WHERE user.user_name = '$username' AND class_permissions.user_id = user.user_id and classes.class_id = class_permissions.class_id";
	$result = mysql_query($query);
	//echo $_SESSION['major'];
	if($_SESSION['major'] != ""){
		echo "<span style='font-size:23px;'>Departments</span><BR>";
		$num = getNumberOfUsers($_SESSION['major']);
		echo "<a onclick=\"pfc.sendRequest('/join ". $_SESSION['major'] . "')\"style='font:14px Tahoma, Sans-serif; text-decoration:none;' href='javascript:void(0);'>". $_SESSION['major']. "</a> - " . $num."<BR>";
	}
	
	echo "<BR>";
	echo "<span style='font-size:23px;'>Classes</span><BR>";
	while($row = mysql_fetch_array($result)){
		$class = $row["class_name"];
		$class = split('-', $class);
		$num = getNumberOfUsers(trim($class[0]));
		echo "<a onclick=\"pfc.sendRequest('/join ". $class[0] . "')\"style='font:14px Tahoma, Sans-serif;text-decoration:none;' href='javascript:void(0);'>". $row['class_name']. "</a> - " . $num."<BR>";
	}

?>
</p>
