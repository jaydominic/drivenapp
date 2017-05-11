<?php

/*
 * 
 *		$userdata variable contains an array with the following fields:
 *		
 *		$userdata['user_id']
 *		$userdata['user_username']
 *		$userdata['user_password']
 *		$userdata['user_firstname']
 *		$userdata['user_lastname']
 *		$userdata['user_middlename']
 *		$userdata['user_fullname']
 *		$userdata['user_email']
 *		$userdata['user_company']
 *		$userdata['user_branch']
 *		$userdata['user_role_id']
 *		$userdata['user_role_name']
 *		$userdata['user_is_active']
 *		$userdata['user_status']
 *		$userdata['user_role_menu_codes']
 */

function update_user_acct($dbconn, $operation, $login_id, $userdata) {
	
	error_log("update_user_acct.php:  START", 0);
	
	// all parameters are required, check first if any are missing / null
	// error_log("update_user_acct.php:  checking parameter [dbconn]", 0);
	if (!isset($dbconn)) {
		error_log("update_user_acct.php:  ERROR -> Parameter [dbconn] missing", 0);
		return false;
	}

	// error_log("update_user_acct.php:  checking parameter [operation]", 0);
	if (!isset($operation)) {
		error_log("update_user_acct.php:  ERROR -> Parameter [operation] missing", 0);
		return false;
	}
	
	// error_log("update_user_acct.php:  checking parameter [login_id]", 0);
	if (!isset($login_id)) {
		error_log("update_user_acct.php:  ERROR -> Parameter [login_id] missing", 0);
		return false;
	}
	
	// error_log("update_user_acct.php:  checking parameter [userdata]", 0);
	if (!isset($userdata)) {
		error_log("update_user_acct.php:  ERROR -> Parameter [userdata] missing", 0);
		return false;
	}
	
/*
	echo "operation=" . $operation . "<br>";
	echo "login_id=" . $login_id . "<br>";
	echo "userdata=<br>";
	var_dump($userdata);
	exit();
*/	
	
	//====================================================================================================
	// check the type of operation being requested
	if ($operation == "ENABLE") {
		
		// check if the user account to enable exists
		$strsql = "UPDATE tbl_login SET login_status = 'Submitted', login_is_active = 'Y', " .
				"login_modified_by_login_id = ?, login_modified_ts = NOW() WHERE login_id = ?";
		
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("update_user_acct.php: Error at mysqli->prepare(UPDATE:ENABLE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("ii", $login_id, $userdata['user_id']);
		if ($stmt->errno <> 0) {
			error_log("update_user_acct.php: Error at stmt->bind_param(UPDATE:ENABLE) -> ERROR: " . $stmt->error, 0);
			return false;
		}
		$stmt->execute();
		error_log("stmt->affected_rows = " . $stmt->affected_rows, 0);
		$numrows = $mysqli->affected_rows;
		if ($numrows == 0) {
			error_log("update_user_acct.php: No rows updated (UPDATE:ENABLE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->close();
		$mysqli->close();
		
		return $numrows;
		
	}
	
	//====================================================================================================
	// check the type of operation being requested
	if ($operation == "DISABLE") {
	
		error_log("update_user_acct.php:  START -> DISABLE user account", 0);
	
		// check if the user account to enable exists
		$strsql = "UPDATE tbl_login SET login_status = 'Disabled', login_is_active = 'N', " .
				"login_modified_by_login_id = ?, login_modified_ts = NOW() " .
				"WHERE login_id = ?";
	
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("update_user_acct.php: Error at mysqli->prepare(UPDATE:DISABLE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("ii", $login_id, $userdata['user_id']);
		if ($stmt->errno <> 0) {
			error_log("update_user_acct.php: Error at stmt->bind_param(UPDATE:DISABLE) -> ERROR: " . $stmt->error, 0);
			return false;
		}
		$stmt->execute();
		$numrows = $mysqli->affected_rows;
		if ($numrows == 0) {
			error_log("update_user_acct.php: No rows updated (UPDATE:DISABLE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->close();
		$mysqli->close();
		
		return $numrows;
	
	}
	
	//====================================================================================================
	
	if ($operation == "UPDATE") {
		
		error_log("update_user_acct.php:  START -> UPDATE user account", 0);

		// check if the user account to enable exists
		$strsql = "UPDATE tbl_login SET login_password = ?, login_firstname = ?, login_lastname = ?, " .
				"login_middlename = ?, login_fullname = ?, login_email = ?, login_company = ?, " .
				"login_branch = ?, login_role_id = ?, login_modified_by_login_id = ?, login_modified_ts = NOW(), " .
				"login_status = ?, login_is_active = ?, " .
				"WHERE login_id = ? AND login_mark_as_deleted IS NULL";
		
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("update_user_acct.php: Error at mysqli->prepare(UPDATE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("ssssssssiissi", $userdata['user_password'], $userdata['user_firstname'],$userdata['user_lastname'],
				$userdata['user_middlename'], $userdata['user_fullname'], $userdata['user_email'], $userdata['user_company'],
				$userdata['user_branch'], $userdata['user_role_id'], $login_id, $userdata['user_status'],
				$userdata['user_is_active'], $userdata['user_id']);
		if ($stmt->errno <> 0) {
			error_log("update_user_acct.php: Error at stmt->bind_param(UPDATE) -> ERROR: " . $stmt->error, 0);
			return false;
		}
		$stmt->execute();
		$numrows = $mysqli->affected_rows;
		if ($numrows == 0) {
			error_log("update_user_acct.php: No rows updated (UPDATE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->close();
		$mysqli->close();
		
		return $numrows;
	}
		
}

?>
