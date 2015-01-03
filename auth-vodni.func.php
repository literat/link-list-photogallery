<?php

/**
 * Simple Link Gallery
 *
 * @author  Tomas Litera  <tomaslitera@hotmail.com>
 */

/**
 * Site specific user authentication
 */

/**
 * Authenticate user over session
 *
 * @param  PDO  $connection  PHP Database Object connection
 * @param  int  $user_id     user id to be checked
 * @return bool              TRUE | FALSE
 */
function sessionUserAuth($connection, $user_id) {
	// check session time and time of its inactivity
	if(!isset($_SESSION[SESSION_PREFIX.'user']) || !isset($_SESSION[SESSION_PREFIX.'password'])) {
		$_SESSION['user']["logged"] = false;
		session_unset();
	} else {
		$_SESSION['user']["logged"] = true;
	}

	if(isset($_SESSION['user']['logged']) && ($_SESSION['user']['logged'] == true)) {
		// do not trust the session from other system
		// authentication of system data
		$sql = "SELECT * FROM `sunlight-users` WHERE id = '".$_SESSION[SESSION_PREFIX.'user']."'";
		$db_user = $connection->prepare($sql);
		$db_user->execute();
		if($db_user->rowCount()) {
			$user = $db_user->fetch(PDO::FETCH_ASSOC);
			if($_SESSION[SESSION_PREFIX.'password'] != $user['password']) {
				header("Location: ".HTTP_DIR."admin/");
				die('Bad password!');
			} else {
				// regenerate time count
				$_SESSION['user']['access_time'] = time();
			}

			if($_SESSION[SESSION_PREFIX.'user'] == $user_id) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		$db_user->closeCursor();
	} else {
		session_unset();
	}

	return FALSE;
}