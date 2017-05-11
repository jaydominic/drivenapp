<?php

/*
 * This will return an array in JSON format with the following fields
 * login_id
 * login_username
 * login_lastname
 * login_firstname
 * login_middlename
 * login_fullname
 * login_email
 * login_company
 * login_branch
 * login_loginrole
 * login_role_id
 * login_is_active
 * login_mark_as_deleted
 * login_status
 * role_name
 * role_description
 */

function getuser($uname, $pword, $dbconn) {
	
	$thisPage = "get_user.php";
	
	error_log($thisPage . ":  uname='" . $uname . "', pword='" . $pword . "'", 0);
	
	// navigate folders based on the location of the currently executing PHP file in the browser
	// in this case it is:  http://localhost/driven-group.com/controller/authenticate.php

	$mysqli = dbconnect($dbconn);
	if ($mysqli == false) {
		// return an error message
		error_log($thisPage . ": dbconnect()==" . $mysqli->errno, 0);
		return false;
	}

	$strsql = "SELECT tbl_login.login_id, tbl_login.login_username, tbl_login.login_lastname, " .
			"tbl_login.login_firstname, tbl_login.login_middlename, tbl_login.login_fullname, tbl_login.login_email, " .
			"tbl_login.login_company, tbl_login.login_branch, tbl_login.login_role, tbl_login.login_role_id, " .
			"tbl_login.login_is_active, tbl_login.login_mark_as_deleted, tbl_login.login_status, " .
			"tbl_role.role_name, tbl_role.role_description " .
			"FROM tbl_login INNER JOIN tbl_role ON tbl_role.role_id = tbl_login.login_role_id " .
			"AND tbl_login.login_username=? AND tbl_login.login_password=?";

	$stmt = $mysqli->prepare($strsql);
	if($mysqli->errno != 0) {
		error_log($thisPage . ": prepare() error " . $mysqli->errno, 0);
	}
	
	$stmt->bind_param("ss", $uname, $pword);
	if($stmt->errno != 0) {
		error_log($thisPage . ": bind_param() error " . $stmt->errno, 0);
	}
	
	$stmt->execute();
	if($stmt->errno != 0) {
		error_log($thisPage . ": execute() error " . $stmt->errno, 0);
	}
	
	$stmt->bind_result($login_id, $login_username, $login_lastname, $login_firstname, $login_middlename, $login_fullname, 
			$login_email, $login_company, $login_branch, $login_role, $login_role_id,	$login_is_active, $login_mark_as_deleted, 
			$login_status, $role_name, $role_description);
	if($stmt->errno != 0) {
		error_log($thisPage . ": bind_result() error " . $stmt->errno, 0);
	}
	
	// Generate the session ID and log to the session database
	session_start();
	session_regenerate_id();
	$session_id = session_id();
	
	// append the SESSION ID to the resulting array
	while ($stmt->fetch()) {
		$resultarray = array('login_id' => $login_id, 'login_username' => $login_username, 'login_lastname' => $login_lastname,	
			'login_firstname' => $login_firstname, 'login_middlename' => $login_middlename, 'login_fullname' => $login_fullname, 
			'login_email' => $login_email, 'login_company' => $login_company, 'login_branch' => $login_branch, 'login_role' => $login_role,	
			'login_role_id' => $login_role_id, 'login_is_active' => $login_is_active, 'login_mark_as_deleted' => $login_mark_as_deleted, 
			'login_status' => $login_status, 'role_name' => $role_name, 'role_description' => $role_description, 'session_id' => $session_id);
	}

	$stmt->close();
	$mysqli->close();
	
	if (!isset($resultarray)) {

		// end the created session
		session_destroy();
		error_log($thisPage . ":  USER NOT FOUND! Destroying session.", 0);
		return false;
	
	} else {
		
		// error_log("get_user():  USER FOUND! session_id==" . $session_id, 0);
		// create a record in the user session table 
		log_session($dbconn, $login_username, $session_id);
		$jsonstr = json_encode($resultarray);
		return $jsonstr;

	}
}

?>
