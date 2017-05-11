<?php

/*
 * This function will return an array object containing the following fields:
 * 
 * login_id 
 * login_username 
 * login_password 
 * login_lastname 
 * login_firstname 
 * login_middlename 
 * login_fullname 
 * login_email
 * login_company 
 * login_branch 
 * login_role_id
 * login_created_by_login_id 
 * login_created_ts
 * login_modified_by_login_id 
 * login_modified_ts 
 * login_is_active
 * login_mark_as_deleted 
 * login_status
 */


function get_list_users($dbconn, $login_id = NULL) {
	
	$mysqli = dbconnect($dbconn);

	if ($login_id == NULL) {
		
		// get ALL user accounts
		$strsql = "SELECT login_id, login_username, login_password, login_lastname, login_firstname, login_middlename, login_fullname, " .
				"login_email, login_company, login_branch, login_role_id, login_created_by_login_id, login_created_ts, " .
				"login_modified_by_login_id, login_modified_ts, login_is_active, login_mark_as_deleted, login_status " .
				"FROM tbl_login WHERE login_mark_as_deleted IS NULL ORDER BY login_username";

		$stmt = $mysqli->prepare($strsql);
		
		$stmt->execute();
		
		$stmt->bind_result($login_id, $login_username, $login_password, $login_lastname, $login_firstname, $login_middlename, $login_fullname,
				$login_email, $login_company, $login_branch, $login_role_id, $login_created_by_login_id, $login_created_ts, $login_modified_by_login_id,
				$login_modified_ts, $login_is_active, $login_mark_as_deleted, $login_status);
		
		while($stmt->fetch()) {
			$resultarray[] = array('login_id' => $login_id, 'login_username' => $login_username, 'login_password,' => $login_password, 
				'login_lastname' => $login_lastname, 'login_firstname' => $login_firstname, 'login_middlename' => $login_middlename, 
				'login_fullname' => $login_fullname, 'login_email' => $login_email, 'login_company' => $login_company, 
				'login_branch' => $login_branch, 'login_role_id' => $login_role_id, 'login_created_by_login_id' => $login_created_by_login_id, 
				'login_created_ts' => $login_created_ts, 'login_modified_by_login_id' => $login_modified_by_login_id, 
				'login_modified_ts' => $login_modified_ts, 'login_is_active' => $login_is_active, 
				'login_mark_as_deleted' => $login_mark_as_deleted, 'login_status' => $login_status);
		}
	
	} else {
		
		// get SPECIFIC user accounts based on login_id
		$strsql = "SELECT login_id, login_username, login_password, login_lastname, login_firstname, login_middlename, login_fullname, " .
				"login_email, login_company, login_branch, login_role_id, login_created_by_login_id, login_created_ts, " . 
				"login_modified_by_login_id, login_modified_ts, login_is_active, login_mark_as_deleted, login_status " .
				"FROM tbl_login WHERE login_id = ? AND login_mark_as_deleted IS NULL";
		
		$stmt = $mysqli->prepare($strsql);
		
		$stmt->bind_param("i", $login_id);
		
		$stmt->execute();
		
		$stmt->bind_result($login_id, $login_username, $login_password, $login_lastname, $login_firstname, $login_middlename, $login_fullname,
				$login_email, $login_company, $login_branch, $login_role_id, $login_created_by_login_id, $login_created_ts, 
				$login_modified_by_login_id, $login_modified_ts, $login_is_active, $login_mark_as_deleted, $login_status);
		
		while($stmt->fetch()) {
			$resultarray[] = array('login_id' => $login_id, 'login_username' => $login_username, 'login_password,' => $login_password,
					'login_lastname' => $login_lastname, 'login_firstname' => $login_firstname, 'login_middlename' => $login_middlename,
					'login_fullname' => $login_fullname, 'login_email' => $login_email, 'login_company' => $login_company, 
					'login_branch' => $login_branch, 'login_role_id' => $login_role_id, 'login_created_by_login_id' => $login_created_by_login_id,
					'login_created_ts' => $login_created_ts, 'login_modified_by_login_id' => $login_modified_by_login_id,
					'login_modified_ts' => $login_modified_ts, 'login_is_active' => $login_is_active,
					'login_mark_as_deleted' => $login_mark_as_deleted, 'login_status' => $login_status);
		}
		
	}
		
	$stmt->close();
	$mysqli->close();
	
	if (!isset($resultarray)) {
		return false;
	} else {
		return $resultarray;
	}
	
}

?>
