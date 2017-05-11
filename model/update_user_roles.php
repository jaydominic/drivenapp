<?php

function update_user_roles($dbconn, $role_id, $operation, $login_id, $role_desc, $cb_menu_code = NULL) {
	
	// check the type of operation being requested
	if ($operation == "ENABLE") {
		
		error_log("update_user_roles.php:  START -> ENABLE role", 0);
		
		// check if the role_id value exists
		$strsql = "SELECT COUNT(role_id) AS role_count FROM tbl_role WHERE role_id = ?";
		
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("update_user_roles.php: Error at mysqli->prepare(SELECT:ENABLE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("i", $role_id);
		if ($stmt->errno <> 0) {
			error_log("update_user_roles.php: Error at stmt->bind_param(SELECT:ENABLE) -> ERROR: " . $stmt->error, 0);
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
			
			$strsql = "UPDATE tbl_role SET role_status = 'Submitted', role_is_active = 'Y', " .
					"role_modified_by_login_id = ?, role_modified_ts = NOW() WHERE role_id = ?";
			
			$mysqli = dbconnect($dbconn);
			$stmt = $mysqli->prepare($strsql);
			if ($mysqli->errno <> 0) {
				error_log("update_user_roles.php: Error at mysqli->prepare(UPDATE:ENABLE) -> ERROR: " . $mysqli->error, 0);
				return false;
			}
			$stmt->bind_param("ii", $login_id, $role_id);
			if ($stmt->errno <> 0) {
				error_log("update_user_roles.php: Error at stmt->bind_param(UPDATE:ENABLE) -> ERROR: " . $stmt->error, 0);
				return false;
			}
			$stmt->execute();
			if ($mysqli->errno == 0) {
				$stmt->close();
				$mysqli->close();
				return true;
			} else {
				error_log("update_user_roles.php: Error enabling role " . $role_id . " -> ERROR No. " . $mysqli->errno, 0);
				$stmt->close();
				$mysqli->close();
				return false;
			}
		
		} else {
		
			error_log("update_user_roles.php: Error getting record count to enable role " . $role_id, 0);
			return false;
			
		}
	}
	
	//====================================================================================================
	
	if ($operation == "DISABLE") {
		
		error_log("update_user_roles.php:  START -> DISABLE role", 0);
		
		// check if the role_id value exists
		$strsql = "SELECT COUNT(role_id) AS role_count FROM tbl_role WHERE role_id = ?";
	
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("update_user_roles.php: Error at mysqli->prepare(SELECT:DISABLE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("i", $role_id);
		if ($stmt->errno <> 0) {
			error_log("update_user_roles.php: Error at stmt->bind_param(SELECT:DISABLE) -> ERROR: " . $stmt->error, 0);
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

			$strsql = "UPDATE tbl_role SET role_status = 'Disabled', role_is_active = 'N', " . 
					"role_modified_by_login_id = ?, role_modified_ts = NOW() WHERE role_id = ?";
			
			$mysqli = dbconnect($dbconn);
			$stmt = $mysqli->prepare($strsql);
			if ($mysqli->errno <> 0) {
				error_log("update_user_roles.php: Error at mysqli->prepare(UPDATE:DISABLE) -> ERROR: " . $mysqli->error, 0);
				return false;
			}
			$stmt->bind_param("ii", $login_id, $role_id);
			if ($stmt->errno <> 0) {
				error_log("update_user_roles.php: Error at stmt->bind_param(UPDATE:DISABLE) -> ERROR: " . $stmt->error, 0);
				return false;
			}
			$stmt->execute();
			if ($mysqli->errno == 0) {
				$stmt->close();
				$mysqli->close();
				return true;
			} else {
				error_log("update_user_roles.php: Error disabling role " . $role_id . " -> ERROR No. " . $mysqli->errno, 0);
				$stmt->close();
				$mysqli->close();
				return false;
			}
	
		} else {
	
			error_log("update_user_roles.php: Error getting record count to disable role " . $role_id, 0);
			return false;
				
		}
	}
		
	//====================================================================================================
	
	if ($operation == "UPDATE") {
		
		error_log("update_user_roles.php:  START -> UPDATE role", 0);

		// update the role description field of tbl_role
		// this assumes we have a valid role record existing in tbl_role table
		$strsql = "UPDATE tbl_role SET role_description = ?, " .
				"role_modified_by_login_id = ?, role_modified_ts = NOW() WHERE role_id = ?";
			
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("update_user_roles.php: Error at mysqli->prepare(UPDATE:UPDATE tbl_roles) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("sii", $role_desc, $login_id, $role_id);
		if ($stmt->errno <> 0) {
			error_log("update_user_roles.php: Error at stmt->bind_param(UPDATE:UPDATE tbl_role) -> ERROR: " . $stmt->error, 0);
			return false;
		}
		$stmt->execute();
		if ($mysqli->errno == 0) {
			$stmt->close();
			$mysqli->close();
		} else {
			error_log("update_user_roles.php: Error updating menu access for role " . $role_id . " -> ERROR No. " . $mysqli->errno, 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}
		
		// check if the role_id value exists in tbl_role_menu table
		$strsql = "SELECT COUNT(role_menu_role_id) AS role_count FROM tbl_role_menu WHERE role_menu_role_id = ?";
		
		$mysqli = dbconnect($dbconn);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log("update_user_roles.php: Error at mysqli->prepare(SELECT:UPDATE) -> ERROR: " . $mysqli->error, 0);
			return false;
		}
		$stmt->bind_param("i", $role_id);
		if ($stmt->errno <> 0) {
			error_log("update_user_roles.php: Error at stmt->bind_param(SELECT:UPDATE) -> ERROR: " . $stmt->error, 0);
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

			// record exists. perform UPDATE on tbl_role table
			$str_menu_codes = "";
			if (isset($cb_menu_code)) {
				foreach ($cb_menu_code as $selected) {
					$str_menu_codes = $str_menu_codes . $selected . ":";
				}
			} else {
				$str_menu_codes = "";
			}
				
			// record exists. perform UPDATE on tbl_role_menu table
			$strsql = "UPDATE tbl_role_menu SET role_menu_menu_codes = ?, " .
					"role_menu_modified_by_login_id = ?, role_menu_modified_ts = NOW() " .
					"WHERE role_menu_role_id = ?";
				
			$mysqli = dbconnect($dbconn);
			$stmt = $mysqli->prepare($strsql);
			if ($mysqli->errno <> 0) {
				error_log("update_user_roles.php: Error at mysqli->prepare(UPDATE:UPDATE tbl_role_menu) -> ERROR: " . $mysqli->error, 0);
				return false;
			}
			$stmt->bind_param("sii", $str_menu_codes, $login_id, $role_id);
			if ($stmt->errno <> 0) {
				error_log("update_user_roles.php: Error at stmt->bind_param(UPDATE:UPDATE tbl_role_menu) -> ERROR: " . $stmt->error, 0);
				return false;
			}
			$stmt->execute();
			if ($mysqli->errno == 0) {
				$stmt->close();
				$mysqli->close();
				return true;
			} else {
				error_log("update_user_roles.php: Error updating menu access for role " . $role_id . " -> ERROR No. " . $mysqli->errno, 0);
				$stmt->close();
				$mysqli->close();
				return false;
			}
		
		} else {
		
			$str_menu_codes = "";
			if (isset($cb_menu_code)) {
				foreach ($cb_menu_code as $selected) {
					$str_menu_codes = $str_menu_codes . $selected . ":";
				}
			} else {
				$str_menu_codes = NULL;
			}
				
			// record does not exist. perform INSERT
			$strsql = "INSERT INTO tbl_role_menu (role_menu_role_id, role_menu_menu_codes, " .
					"role_menu_modified_by_login_id, role_menu_modified_ts) " . 
					"VALUES (?, ?, ?, NOW())";
			
			$mysqli = dbconnect($dbconn);
			$stmt = $mysqli->prepare($strsql);
			if ($mysqli->errno <> 0) {
				error_log("update_user_roles.php: Error at mysqli->prepare(UPDATE:INSERT) -> ERROR: " . $mysqli->error, 0);
				return false;
			}
			$stmt->bind_param("isi", $role_id, $str_menu_codes, $login_id);
			if ($stmt->errno <> 0) {
				error_log("update_user_roles.php: Error at stmt->bind_param(UPDATE:INSERT) -> ERROR: " . $stmt->error, 0);
				return false;
			}
			$stmt->execute();
			if ($mysqli->errno == 0) {
				$stmt->close();
				$mysqli->close();
				return true;
			} else {
				error_log("update_user_roles.php: Error inserting menu access for role " . $role_id . " -> ERROR No. " . $mysqli->errno, 0);
				$stmt->close();
				$mysqli->close();
				return false;
			}
				
		}
		

	}

}

?>
