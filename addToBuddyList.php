<?php
	mysql_connect("localhost","mia","soulskater") or die(mysql_error());
	mysql_select_db("social_study_groups") or die(mysql_error());
	session_start();
	$user = $_GET['name'];
	$myName = $_SESSION['username'];
	$query = "Select * from study_buddies as sb where sb.user_id = (select user_id from user where user.user_name = '$myName') AND sb.buddy_id = (Select user.user_id from user where user.user_name = '$user')";
	$result = mysql_query($query);
 	//$row = mysql_fetch_array($result);
 	if(mysql_num_rows($result) > 0){
 		echo "already a friend";
 	}else{
 		$insertQuery = "Insert into study_buddies(user_id,buddy_id)VALUES((Select user_id from user where user.user_name = '$myName'), (Select user_id from user where user.user_name = '$user'))";
 		$insertResult = mysql_query($insertQuery);
 	}
 	header("Location: index.php?");
	exit;
?>
