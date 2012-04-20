<?php
mysql_connect("localhost","mia","soulskater") or die(mysql_error());
mysql_select_db("social_study_groups") or die(mysql_error());
session_start();
$user = $_POST['username'];
$password = $_POST['password'];
$query = "Select * from user where user_name = '$user' and password = '$password'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
if(mysql_num_rows($result) > 0){
	$_SESSION['username'] = $row['user_name'];
	$_SESSION['name'] = $row['full_name'];
	$_SESSION['loggedIn'] = 'true';
	$_SESSION['major'] = $row['major'];
}
?>


<?php
header("Location: index.php?");
exit;
?>
