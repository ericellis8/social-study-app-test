<u style='font:15px Tahoma, Sans-serif;'>Buddy List
<?php
	mysql_connect("localhost","mia","soulskater") or die(mysql_error());
	mysql_select_db("social_study_groups") or die(mysql_error());
	session_start();
	$username = $_GET['username'];
	$query = "Select * from user as u, study_buddies as sb where u.user_id = sb.buddy_id AND sb.user_id = (Select user_id from user where user.user_name = '$username')";
	$result = mysql_query($query);
	$buddyArray = array();
	function getNicknames($nickId) 
	{
		$serverid = isset($GLOBALS['serverid']) ? $GLOBALS['serverid'] : 0;
		require_once dirname(__FILE__)."/phpFreeChat/src/pfcinfo.class.php";
		$info  = new pfcInfo( md5("pupchat") );
		$listOnline = $info->getOnlineNick(NULL);
		return $listOnline;
	}
	$users = getNicknames(1);
	$userArray = array();
	$num_users = 0;
	while($row = mysql_fetch_array($result)){
		$yes = false;
		for($i = 0; $i<count($users); $i++){
			if($row["user_name"] == $users[$i]){
				array_push($userArray, $row["user_name"]);
				$num_users++;
				$yes = true;
			}	
		}	
		if($yes != true){	
			array_push($buddyArray, $row["user_name"]);
		}	
	}
	echo " - " . $num_users . " Online</u>";
	echo "<BR><span id='here'>";
	foreach($userArray as $user){
		echo "<div style='font:15px Tahoma, Sans-serif;color:blue;'>";
		echo "<a onclick=\"pfc.sendRequest('/privmsg ". $user . "')\" href= 'javascript:void(0);' style = 'text-decoration:none;'>" . $user . "</a>";
		//echo "<a onclick=\"updateNickWhoisBox('904cb17acbe02c2ad452f5be0901e317d031502f')\" href= 'javascript:void(0);' style = 'text-decoration:none;'>" . $user . "</a>";
		echo "</div>";
	}
	?>
	<script type='text/javascript'>
			setInterval("getUser()",1000);
		</script>
	<?php
	
	for($i = 0; $i < count($buddyArray); $i++){
		echo "<div style='font:15px Tahoma, Sans-serif;color:grey'>";
		echo $buddyArray[$i];
		echo "</div>";
	}
	echo "</span>";

?>

