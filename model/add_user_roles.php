<?php

function add_user_roles($dbconn, $role_name, $role_desc, $login_id, $cb_menu_code = NULL) {
	
	error_log("add_user_roles.php:  START -> INSERT new role", 0);

	// check if the role_name value already exists
	$strsql = "SELECT COUNT(role_id) AS role_count FROM tbl_role " .
			"WHERE role_name = ? AND role_mark_as_deleted IS NULL";

	$mysqli = dbconnect($dbconn);
	$stmt = $mysqli->prepare($strsql);
	if ($mysqli->errno <> 0) {
		error_log("add_user_roles.php: Error at mysqli->prepare(SELECT:INSERT) -> ERROR: " . $mysqli->error, 0);
		return false;
	}
	$stmt->bind_param("s", $role_name);
	if ($stmt->errno <> 0) {
		error_log("add_user_roles.php: Error at stmt->bind_param(SELECT:INSERTE) -> ERROR: " . $stmt->error, 0);
		return false;
	}
	$stmt->execute();
	$stmt->bind_result($role_count);
	while($stmt->fetch()) {
		$role_id_count = $role_count;
	}
	$stmt->close();
	$mysqli->close();

	if ($role_id_count > 0) {

		// do not proceed with INSERT
		error_log("add_user_roles.php: ERROR:  Duplicate role name (" . $role_name . ") found.", 0);;
		return false;

	} else {  // continue with INSERT operation
		
		// role name is unique.  perform INSERT role
		
		$strsql = "INSERT INTO tbl_role (role_name, role_description, role_created_by_login_id, role_is_active, role_status, role_created_ts) " .
				"VALUES (?, ?, ?, 'Y', 'Submitted', NOW())";
		
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("add_user_roles.php: Error at mysqli->prepare(INSERT:tbl_role) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("ssi", $role_name, $role_desc, $login_id);
		if ($stmt->errno <> 0) {
			error_log("add_user_roles.php: Error at stmt->bind_param(INSERT:tbl_role) -> ERROR: " . $stmt->error, 0);
			return false;
		}
		$stmt->execute();
		if ($mysqli->errno == 0) {
			$stmt->close();
			$mysqli->close();
		} else {
			error_log("add_user_roles.php: Error inserting role " . $role_name . " -> ERROR No. " . $mysqli->errno, 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}
		
		// get the role_id of the new role name
		
		$strsql = "SELECT role_id FROM tbl_role " .
				"WHERE role_name = ? AND role_description = ? AND role_mark_as_deleted IS NULL";
			
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("add_user_roles.php: Error at mysqli->prepare(SELECT:get role_id of " . $role_name . ") -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("ss", $role_name, $role_desc);
		if ($stmt->errno <> 0) {
			error_log("add_user_roles.php: Error at stmt->bind_param(SELECT:get role_id of " . $role_name . ") -> ERROR: " . $stmt->error, 0);
			return false;
		}
		$stmt->execute();
		$stmt->bind_result($role_id);
		while($stmt->fetch()) {
			$new_role_id = $role_id;
		}
		$stmt->close();
		$mysqli->close();
			
		if (!isset($new_role_id)) {
			// something went wrong, cannot find the role_id of the newly created role
			error_log("add_user_roles.php: ERROR -> cannot find role_id of " . $role_name, 0);
			return false;
				
		} else {
			
			// insert menu codes into tbl_role_menu
			
			if (!isset($cb_menu_code)) {
				
				// insert a record with no access to anything
				$str_menu_codes = NULL;
				
			} else {
			
				// insert a record with the specified menu codes from cb_menu_code parameter
				$str_menu_codes = "";
				if (isset($cb_menu_code)) {
					foreach ($cb_menu_code as $selected) {
						$str_menu_codes = $str_menu_codes . $selected . ":";
					}
				}
			}
				
			$strsql = "INSERT INTO tbl_role_menu (role_menu_role_id, role_menu_menu_codes, " .
					"role_menu_modified_by_login_id, role_menu_modified_ts) " .
					"VALUES (?, ?, ?, NOW())";
	
			$mysqli = dbconnect($dbconn);
			$stmt = $mysqli->prepare($strsql);
			if ($mysqli->errno <> 0) {
				error_log("add_user_roles.php: Error at mysqli->prepare(INSERT:tbl_role_menu) -> ERROR: " . $mysqli->error, 0);
				return false;
			}
			$stmt->bind_param("isi", $role_id, $str_menu_codes, $login_id);
			if ($stmt->errno <> 0) {
				error_log("add_user_roles.php: Error at stmt->bind_param(INSERT: tbl_role_menu) -> ERROR: " . $stmt->error, 0);
				return false;
			}
			$stmt->execute();
			if ($mysqli->errno == 0) {
				$stmt->close();
				$mysqli->close();
				return true;
			} else {
				error_log("add_user_roles.php: Error inserting menu access for role " . $role_id . " -> ERROR No. " . $mysqli->errno, 0);
				$stmt->close();
				$mysqli->close();
				return false;
			}

		}
			
	}
		
}

?>
