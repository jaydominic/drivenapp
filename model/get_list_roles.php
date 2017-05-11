<?php

function get_list_roles($dbconn, $role_id = NULL) {
	
	$mysqli = dbconnect($dbconn);

	if ($role_id == NULL) {
	
		// return all roles except those marked as deleted
		$strsql = "SELECT tbl_role.role_id, tbl_role.role_name, tbl_role.role_description, tbl_role.role_status, " .
				"tbl_role.role_is_active, tbl_role_menu.role_menu_menu_codes " . 
				"FROM `tbl_role` LEFT JOIN tbl_role_menu ON tbl_role_menu.role_menu_role_id = tbl_role.role_id " .
				"WHERE tbl_role.role_mark_as_deleted IS NULL";

		$stmt = $mysqli->prepare($strsql);
		
		$stmt->execute();
		
		$stmt->bind_result($role_id, $role_name, $role_description, $role_status, $role_is_active, $role_menu_menu_codes);
		
		while($stmt->fetch()) {
			$resultarray[] = array('role_id' => $role_id, 'role_name' => $role_name, 'role_description' => $role_description, 'role_status' => $role_status, 'role_is_active' => $role_is_active, 'role_menu_menu_codes' => $role_menu_menu_codes);
		}
	
	} else {
		
		$strsql = "SELECT tbl_role.role_id, tbl_role.role_name, tbl_role.role_description, tbl_role.role_status, " .
				"tbl_role.role_is_active, tbl_role_menu.role_menu_menu_codes " .
				"FROM `tbl_role` INNER JOIN tbl_role_menu ON tbl_role_menu.role_menu_role_id = tbl_role.role_id " . 
				"WHERE tbl_role.role_id = ? AND tbl_role.role_mark_as_deleted IS NULL";
	
		$stmt = $mysqli->prepare($strsql);
		
		$stmt->bind_param("i", $role_id);
		
		$stmt->execute();
		
		$stmt->bind_result($role_id, $role_name, $role_description, $role_status, $role_is_active, $role_menu_menu_codes);
		
		while($stmt->fetch()) {
			$resultarray[] = array('role_id' => $role_id, 'role_name' => $role_name, 'role_description' => $role_description, 'role_status' => $role_status, 'role_is_active' => $role_is_active, 'role_menu_menu_codes' => $role_menu_menu_codes);
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
