<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

/**
* Mia-Chat database class.  Helps support Mia-chat specific database interactions
* via the adodb portability layer.
*
* Notes: 
* 1) MS SQL Server - Not currently support do to a adodb bug with GetAssoc (different output format).
* 2) Postgres - Had to modify the adodb postgres driver to support Last insert ID: http://phplens.com/lens/lensforum/msgs.php?id=16767
*/
class MiaChatDb {
	
	private static $instance;
	private $dbvendor;

	private function __construct() {
		if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
		   @date_default_timezone_set(@date_default_timezone_get());
		}
		
		$this->db = $this->getConnection();
	}
	
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	* Used to build a connection to the Mia-Chat database
	*/
	private function getConnection() {
		// Parse the Mia-Chat config file
		$ini_array = parse_ini_file("config.ini.php", true);
		$host = $ini_array['database_info']['host'];
		$user = $ini_array['database_info']['user'];
		$password = $ini_array['database_info']['password'];
		$database = $ini_array['database_info']['database'];
		
		$this->dbvendor = $ini_array['database_info']['vendor'];

		include('includes/adodb5/adodb.inc.php');
		$db = ADONewConnection($this->dbvendor);
		$db->PConnect($host, $user, $password, $database);
		//$db->debug = true;
		return $db;
	}
	
	/**
	* Used to verify a new user's requested username doesn't already exist
	* @param username - desired username
	*/
	function uniqueUsernameCheck($username) {
		$clnUsername = $this->escapeForDb($username);
		$usernameSQL = "SELECT count(*) FROM mia_users WHERE username={$clnUsername}";
		$user = $this->executeSQL($usernameSQL);
		$userCount = intval($user->fields[0]);
	    if ($userCount>0) {
			return false; //username is not unique
		}
	}
	
	/**
	* Performs the actual Mia-Chat login, updates the password salt, and calls session setup
	* @param username
	* @param password
	*/
	function userLogin($username, $password) {
		$clnUsername = $this->escapeForDb($username);
		$loginSQL = "SELECT id, full_name, username, salt, password, usergroup, show_offline_buddies FROM mia_users WHERE username={$clnUsername}";
		$user = $this->executeSQL($loginSQL);
		if (empty($user)) {
			return false;
		} else {	
			$dbSalt = $user->fields[3];
			$dbPassword = $user->fields[4]; 
		}
		
		//Try to recreate password for matching
		$inputHashedPassword = sha1($password . $dbSalt);
		//Test db password against the one entered on the form
		if ($inputHashedPassword !== $dbPassword) {
			return false;
		} else {
			//We have a match, now update/rerandomize the salt and password for added security
			$newPasswordArray = $this->buildPassword($password);
			$newSalt = $this->escapeForDb($newPasswordArray['salt']);
			$newPasswordHash = $this->escapeForDb($newPasswordArray['password']);
			$now = $this->escapeForDb(date("Y-m-d H:i:s"));
			$updateSQL = "UPDATE mia_users 
							SET password = {$newPasswordHash},
							salt = {$newSalt}, 
							heartbeat = {$now},
							status = 'online',
							password_reset_key = ''
							WHERE username={$clnUsername}";
			if ($this->executeSQL($updateSQL)===false) {
                return false;
            }
		}
		$userid = $user->fields[0];
		$fullname = $user->fields[1];
		$username = $user->fields[2];
		$usergroup = $user->fields[5];
		$showoffline = $user->fields[6];
		$env = sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
		$this->setupSession($userid, $fullname, $username, $usergroup, $env, $showoffline);
	}
	
	/**
	* Creates a new account
	* @param fullname
	* @param username
	* @param email
	* @param password
	*/
	function createUserAccount($fullname, $username, $email, $password, $timeoffset) {
		$clnFullname = $this->escapeForDb($fullname);
		$clnUsername = $this->escapeForDb($username);
		$clnEmail = $this->escapeForDb($email);
		
		$passwordArray = $this->buildPassword($password);
		$clnSalt = $this->escapeForDb($passwordArray['salt']);
		$clnPasswordHash = $this->escapeForDb($passwordArray['password']);
		$now = $this->escapeForDb(date("Y-m-d H:i:s"));
		
		$userSQL = "INSERT INTO mia_users (full_name, username, password, salt, email, usergroup, time_offset, create_date)
             VALUES ({$clnFullname}, {$clnUsername}, {$clnPasswordHash}, {$clnSalt}, {$clnEmail}, 1, {$timeoffset}, {$now})";
		$result = $this->executeSQL($userSQL);
		if (!$result) {
			return false;
		}
	}
	
	/**
	* Quotes a string to be sent to the database
	* @param string
	*/
	function escapeForDb($someString) {
		return $this->db->qstr($someString, get_magic_quotes_gpc());
	}
	
	/**
	* Build a new random salt value
	*/
	function getSalt() {
		$validSalt = 'acbdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ1234567890^%()!~.<>?_#@';
		$saltLength=strlen($validSalt);
		
		//We want an 8 character salt key mixed from the values above
		$salt='';
		for ($i = 0; $i<8; $i++) {
			//pick a random number between 0 and the max of validsalt
			$rand=mt_rand(0, $saltLength);
			//grab the char at that position
			$selectedChar=substr($validSalt, $rand, 1);
			$salt = $salt . $selectedChar;
		}
		return $salt;
	}
	
	/**
	* Build hashed password array
	* @param password (plain text)
	*/
	function buildPassword($password) {
		$salt = $this->getSalt();
		$hashedPassword = sha1($password . $salt);
		$passwordArray = array('salt'=>$salt, 'password'=>$hashedPassword);
		return $passwordArray;
	}
	
	/**
	* Used to update a users login status
	* @param status
	*/
	function updateStatus($status) {
		$crtUserID = $this->getCrtUserID();
		$clnStatus = $this->escapeForDb($status);
		$statusSQL = "UPDATE mia_users
						SET status = {$clnStatus}
						WHERE id = {$crtUserID}";
		
		$result = $this->executeSQL($statusSQL);
		if (!$result) {
			return false;
		}
	}
	
	/**
	* Retrieves user messages
	*/
	function getMessages() {
		$crtUserID = $this->getCrtUserID();
		$messageSQL = "SELECT m.id as mid, u.username AS username_to, u2.username as username_from, 
						m.message, m.rand_insert_key, m.sent_date_time, u.time_offset as to_timeoffset, u2.time_offset as from_timeoffset
						FROM mia_messages m, mia_users u, mia_users u2 
						WHERE m.userid_to = u.id 
						AND m.userid_from = u2.id
						AND m.userid_to = {$crtUserID}";
		
		$messages = $this->executeGetAssocSQL($messageSQL);
		if ($messages) { 
            foreach($messages as $key=>$value) {
        		$this->removeMessage($crtUserID, $value['mid']);
        	}
        }
        
		return $messages;
	}
	
	/**
	* Add new message for delivery
	* @param recipientUsername - intended user target
	* @param message
	* @param rand - random string delivered with message used to callback for delete after delivery
	*/
	function addMessage($recipientUsername, $message, $rand) {
		$fromId = $this->getCrtUserID();
		$toId = $this->getUserIDFromUsername($recipientUsername);
		$clnMessage = $this->escapeForDb($message);
		$clnRand = $this->escapeForDb($rand);
		$now = $this->escapeForDb(date("Y-m-d H:i:s"));
		$messageSQL = "INSERT INTO mia_messages (userid_from, userid_to, message, rand_insert_key, sent_date_time) 
		VALUES ({$fromId}, {$toId}, {$clnMessage}, {$clnRand}, {$now})";
		
		$result = $this->executeSQL($messageSQL);
		if ($result) {
			$insertID = $this->db->Insert_ID("mia_messages", "id");
			$lastMessage = "SELECT id as mid, userid_from, userid_to, message, rand_insert_key, sent_date_time
							FROM mia_messages 
							WHERE id = {$insertID}";
			$message = $this->executeGetAssocSQL($lastMessage);
			return $message;
		} else {
			return false;
		}
	}
	
	/**
    * Removes a message after it has been successfully retrieved
    * @param messageID
    */
    function removeMessage($crtUserID, $messageID) {	
    	$removalSQL = "DELETE FROM mia_messages 
    					WHERE id={$messageID} 
    					AND userid_to={$crtUserID}";

    	$result = $this->executeSQL($removalSQL);
    	if (!$result) {
    		return false;
    	}
    }
	
	/**
	* Get buddies associated with a user
	* @param showoffline - effects what uses are returned
	*/
	function getBuddies($showoffline) {
		$userid = $this->getCrtUserID();
		
		$buddySQL = "SELECT u.id as bid, u.username, u.full_name, u.email, heartbeat, status
						FROM mia_buddies b, mia_users u
						WHERE b.buddy_userid = u.id
						AND b.userid={$userid}";
						
		if ($showoffline==0) {
				$buddySQL .= " AND status not in ('offline')";
		}
		
		$buddies = $this->executeGetAssocSQL($buddySQL);
		
		/**
		* We need to do some date math and turn the heartbeat into seconds. If not set as offline
		* specifically, but has not had a heartbeat within the last 120 seconds then consider them offline.
		*
		* Note: Easier to do in PHP than try to pull of in a portable way accross different database platforms.
		*/
		$now = strtotime(date("Y-m-d H:i:s"));
		foreach($buddies as $buddy) {
		    $secsSinceHeartbeat = $now - strtotime(date("Y-m-d H:i:s", strtotime($buddy["heartbeat"])));
		    if ($secsSinceHeartbeat >=120 && $showoffline==0) {
		        unset($buddies[$buddy["bid"]]);
		    } else {
		        $buddies[$buddy["bid"]]["heartbeat"] = $secsSinceHeartbeat;
		    }
		}
		
		return $buddies;
	}
	
	/**
	* Add new buddy for a user
	* @param buddyid - id of user to setup as buddy
	*/
	function addNewBuddy($buddyid) {
		$buddyid=intval($buddyid);
		$userid=$this->getCrtUserID();
		
		//Users can't add themselves as a buddy
		if ($buddyid==$userid) {
			return false;
		}
		
		//Verify this row does not already exit
		$checkSQL = "SELECT count(*) 
						FROM mia_buddies 
						WHERE userid={$userid} 
						AND buddy_userid={$buddyid}";
					
		$check = $this->executeSQL($checkSQL);
		if ($check->fields[0]>0) {
			return false;
		} else {
			//Add the new row
			$buddySQL = "INSERT INTO mia_buddies (userid, buddy_userid) VALUES ({$userid}, {$buddyid})";
			$result = $this->executeSQL($buddySQL);
			return $result;
		}
	}
	
	/**
	* Remove buddy relationship for user
	* @param buddyid - id of user to remove as buddy
	*/
	function removeBuddy($buddyid) {
		$buddyid=intval($buddyid);
		$userid=$this->getCrtUserID();
		
		//Add the new row
		$buddySQL = "DELETE FROM mia_buddies
						WHERE userid = {$userid}
						AND buddy_userid = {$buddyid}";
		$result = $this->executeSQL($buddySQL);
		return $result;
	}
	
	/**
	* Search for new buddy
	* @param fullname
	* @param username
	* @param email
	*/
	function searchBuddies($fullname, $username, $email) {
		if ($fullname!='') {
			$clnFullname = $this->escapeForDb('%'.$fullname.'%');
		} else {
			$clnFullname = $this->escapeForDb($fullname);
		}
		if ($username!='') {
			$clnUsername = $this->escapeForDb('%'.$username.'%');
		} else {
			$clnUsername = $this->escapeForDb($username);
		}
		if ($email!='') {
			$clnEmail = $this->escapeForDb('%'.$email.'%');
		} else {
			$clnEmail = $this->escapeForDb($email);
		}
		
        //Parse the Mia-Chat config file
        $ini_array = parse_ini_file("config.ini.php", true);
        //Do we want to show the user emails or not ?
        $show_user_emails = $ini_array['global_info']['show_user_emails'];
        
        if ($show_user_emails === true) {
            $buddySQL = "SELECT id as bid, full_name, username, email
						FROM mia_users
						WHERE full_name LIKE {$clnFullname}
						OR username LIKE {$clnUsername}
						OR email LIKE {$clnEmail}";
         } else {
            $buddySQL = "SELECT id as bid, full_name, username, 'xxx@yyy.zzz' as email
						FROM mia_users
						WHERE full_name LIKE {$clnFullname}
						OR username LIKE {$clnUsername}
						OR email LIKE {$clnEmail}";
         }
                         
						
		$buddies = $this->executeGetAssocSQL($buddySQL);
		return $buddies;
	}
	
	/**
	* Wrapper around the ADOdb execute function.  Takes a SQL string and executes it against the database.
	* Note: Error checking not performed on the function.  That should be handled by the calling routines.
	* @param sql string
	*/
	function executeSQL($sql) {
	    $result = $this->db->Execute($sql);
        return $result;
	}
	
	/**
	* Wrapper around the ADOdb GetAssoc function.  Takes a SQL string and executes it against the database.
	* Returns an associatd array of data.
	* Note: Error checking not performed on the function.  That should be handled by the calling routines.
	* @param sql string
	*/
	function executeGetAssocSQL($sql) {
		$results = $this->db->GetAssoc($sql, false, false, false);
		return $results; //False on failure
	}
	
	/**
	* Sets up session variables need throughout the appication.  Called at login.
	* @param userid
	* @param usergroup
	* @param env - sha1(useragent.ipaddress)
	* @param showoffline	
	*/
	function setupSession($userid, $fullname, $username, $usergroup, $env, $showoffline) {
		$_SESSION["userid"]=$userid;
		$_SESSION["fullname"]=$fullname;
		$_SESSION["username"]=$username;
		$_SESSION["usergroup"]=$usergroup;
		$_SESSION["env"]=$env;
		$_SESSION["showoffline"]=$showoffline;
	}
	
	/**
	* Used to help control access to certin functions within the application
	* @param minAllowedGroup - the lowest level allowed
	* Note: not in use at the moment, but may be in the future if we develop an admin interface
	*/
	function minGroupLevelCheck($minAllowedGroup) {
		$minAllowedGroup = intval($minAllowedGroup);
		if (isset($_SESSION["usergroup"])) {
			$crtUserGroup=intval($_SESSION["usergroup"]);
		} else {
			$crtUserGroup=0;
		}
		
		if ($crtUserGroup<$minAllowedGroup) {
			return false;
		}
	}
	
	/**
	* Compares current env info against that regisitered at login.  Change indicates a hijack attempt.
	*/
	function sessionHijackCheck() {
        if (isset($_SESSION["env"])) {
            $loginEnv = $_SESSION["env"];
        } else {
            $loginEnv = '';
        }
		$crtEnv = sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
		
		//Same browser and IP address?
		if ($loginEnv!==$crtEnv) {
			return false;
		}
	}
	
	/**
	* Returned the userid of the current user
	*/
	function getCrtUserID() {
		$userid = '';
		if (isset($_SESSION["userid"]) && !empty($_SESSION["userid"])) {
			$userid = intval($_SESSION["userid"]);
		}
		return $userid;
	}
	
	/**
	* Returns the username associated with a specific userid
	* @param username
	*/
	function getUserIDFromUsername($username) {
		$username = $this->escapeForDb($username);
		$userIDSQL = "SELECT id from mia_users WHERE username={$username}";
		$results = $this->executeSQL($userIDSQL);
		return $results->fields[0];
	}
	
	function passwordReset($username, $email) {
		$userIdSQL = "SELECT id 
		                FROM mia_users 
		                WHERE username={$this->escapeForDb($username)}
		                AND email={$this->escapeForDb($email)}";
		$user = $this->executeSQL($userIdSQL);
		$userID = intval($user->fields[0]);
	    if ($userID>0) {
		    //Generate a new password
		    $salt = $this->getSalt();
		    $passwordResetKey = $this->buildPassword($salt);
		    $updateSQL = "UPDATE mia_users
		                    SET password_reset_key='{$passwordResetKey['password']}'
		                    WHERE id={$userID}";
		    if ($this->executeSQL($updateSQL)===false) {
                return false;
            } 
            
            if ($this->emailPassword($username, $passwordResetKey['password'], $email)===false) {
                return false; //Unable to send email
            } 
        } else {
            return false; //Invalid username and/or email
        }
	}
	
	function updatePassword($username, $email, $newPassword, $activationCode) {	  
	    $clnUsername =  $this->escapeForDb($username);
	    $clnEmail =  $this->escapeForDb($email);
	    $activationCode = $this->escapeForDb($activationCode);
		$userIdSQL = "SELECT id 
		                FROM mia_users 
		                WHERE username={$clnUsername}
		                AND email={$clnEmail}
		                AND password_reset_key={$activationCode}";
		$user = $this->executeSQL($userIdSQL);
		$userID = intval($user->fields[0]);
	    if ($userID>0) {
		    //Update password
		    $passwordArray = $this->buildPassword($newPassword);
		    $salt = $passwordArray['salt'];
		    $password = $passwordArray['password'];
		    $updateSQL = "UPDATE mia_users
		                    SET salt='{$salt}',
		                    password='{$password}',
		                    password_reset_key=''
		                    WHERE id={$userID}";
		    if ($this->executeSQL($updateSQL)===false) {
                return false;
            }
        } else {
            return false; //Invalid username, email, and/or activation code
        }
	}
	
	function emailPassword($username, $passwordResetKey, $email) {   
	    //Parse the Mia-Chat config file
		$ini_array = parse_ini_file("config.ini.php", true);
		$adminEmail = $ini_array['global_info']['admin_email'];
		$siteUrl = $ini_array['global_info']['live_site_url'];
		if (empty($adminEmail) || empty($siteUrl)) {
		    die('Invalid mail configuration!');
		}
		
		if (substr($siteUrl, -1, 1)!='/') {
		    //Add a trailing slash of not setup in the configuration file
		    $siteUrl .= '/';
		}
		$siteResetUrl .= "changePassword.php?user=$username&email=$email&activation_code=$passwordResetKey";
		
        //The message
        $message = "A Mia-Chat password reset request has been requested for this email address at $siteUrl. ";
        $message .= "If this was not you then simply ignore this request.  If you did make this request ";
        $message .= "then click the link below to finish the reset of this process:\n\n ";
        $message .= $siteUrl.$siteResetUrl;
        //In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70);
        $headers = "From:". $adminEmail . "\n" .
                    "Reply-To:". $adminEmail . "\n" .
                    "X-Mailer: PHP/" . phpversion();
        //Send email
        if (mail($email, 'Mia-Chat Password Reset Request', $message, $headers)===false) {
           return false;
        }
	}
	
	/**
	* Like the pulse of a heart this is used to periodically pulse/checkin and 
	* tell the app a user is still alive/online
	*/
	function heartbeat() {
		$userid = $this->getCrtUserID();
		$now = $this->escapeForDb(date("Y-m-d H:i:s"));
		$heartbeatSQL = "UPDATE mia_users 
							SET heartbeat = {$now}
							WHERE id={$userid}";
		if ($this->executeSQL($heartbeatSQL)===false) {
            return false;
        }
	}
	
    /**
    * Returns a simple db result array for basic user info
    * username, fullname, email  
    */
    function getUserProfile($userid) {
        $userSQL = "SELECT id, full_name, username, email
                FROM mia_users WHERE id={$userid}";
        $results = $this->executeGetAssocSQL($userSQL);
        if ($results === false) {
            return false;
        } else {
            return $results;
        }
    }


    /**
    * Returns a simple db result array for basic user info
    * username, fullname, email  
    */
    function getBuddyDetails($userid) {
        
        //don't give anything back, unless the signed on user is in the other parties list
        
    }
    
    
    /**
    * Check the userid, username and password trio 
    */
    function checkUser($userid, $username, $password) {
        $clnUserid = intval($userid);
        $clnUsername =  $this->escapeForDb($username);
        $userSQL = "SELECT id, username, password, salt FROM mia_users WHERE id={$clnUserid} AND username={$clnUsername}";
		$user = $this->executeSQL($userSQL);
		if (empty($user)) {
			return false;
		} else {	
			$dbSalt = $user->fields[3];
			$dbPassword = $user->fields[2]; 
		}
         
        //Try to recreate password for matching
		$inputHashedPassword = sha1($password . $dbSalt);
		//Test db password against the one entered on the form
		if ($inputHashedPassword !== $dbPassword) {
			return false;
		}
    }
    
    
    /**
    * Update the full and the email
    */
    function updateUserProfile($userid, $fullname, $email, $timeoffset) {
        if ($userid !== $this->getCrtUserID()) {
            return false;
        } else {
            $clnFullname =  $this->escapeForDb($fullname);
            $clnEmail =  $this->escapeForDb($email);
            $clnTimeOffset =  $this->escapeForDb($timeoffset);
            $profileSQL = "UPDATE mia_users 
                                SET full_name = {$clnFullname}, 
                                email = {$clnEmail},
                                time_offset = {$clnTimeOffset}  
                                WHERE id={$userid}";                                
            if ($this->executeSQL($profileSQL)===false) {
                return false;
            }  
        }
        return true;
    }
} 
?>