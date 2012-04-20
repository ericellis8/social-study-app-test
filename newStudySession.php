<?php

        mysql_connect("localhost","mia","soulskater") or die(mysql_error());
        mysql_select_db("social_study_groups") or die(mysql_error());
        session_start();
        $username = $_SESSION["username"];
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



?>

<!DOCTYPE HTML>
<html>
<head>

<style type="text/css">
body
{
background-color:#F0F8FF;
}
div.newStudySession
{
text-align: center;
}
.button
{
position: absolute;
  bottom: 50px;
  width: 50%;
  margin-left:auto;
  margin-right:auto;
}
</style>

</head>

<body>

<div class="newStudySession">
<form>
<h1 align="center">Begin A New Study Session!</h1>
<h3>Step 1: Enter the Name of the Study Session</h3>
<input type="text" name="sessionName" /><br />
<h3>Step 2: Select Study Buddies to Invite</h3>
Choose from your online study buddies:<br /><br />
<table border="1" width="100%">
<?php
if(sizeof($userArray)>0){
	echo "<tr>";
}
for ($i=0; $i<sizeof($userArray); $i++) {
	$iteration++;
	if($i == 3){
		echo "</tr><tr>";
	}
	echo "<td><a href='www.yahoo.com'>yahoo</td>";

}
echo "</tr>";
?>
<!--<tr>
<td>row 1, cell 1</td>
<td>row 1, cell 2</td>
<td>row 1, cell 3</td>
</tr>
<tr>
<td>row 2, cell 1</td>
<td>row 2, cell 2</td>
<td>row 2, cell 3</td>
</tr>
<tr>
<td>row 3, cell 1</td>
<td>row 3, cell 2</td>
<td>row 3, cell 3</td>
</tr>
<tr>
<td>row 4, cell 1</td>
<td>row 4, cell 2</td>
<td>row 4, cell 3</td>
</tr>
<tr>
<td>row 5, cell 1</td>
<td>row 5, cell 2</td>
<td>row 5, cell 3</td>
</tr>-->
</table>
<br /><br />
And/or select students to join the session from...<br /><br />

<table border="1" width="100%">
<tr>
<td>A Class:</td>
<td>Major/Subject:</td>
<td>Search For Someone:</td>
</tr>
<tr>
<td>
<select>
  <option>Class 1</option>
  <option>Class 2</option>
  <option>Class 3</option>
</select>
</td>
<td>
<select>
  <option>Subject 1</option>
  <option>Subject 2</option>
  <option>Subject 3</option>
</select>
</td>
<td>
Enter A Username:<input type="text" name="searchForStudent" />
</td>
</tr>
</table>
<br />
</form>
<button type="button" class="button" align="middle" />Begin The Study Session!</button>
</div>


</body>

</html>

