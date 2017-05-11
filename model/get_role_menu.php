<?php

function get_role_menu($role_id, $dbconn) {
	
	$mysqli = dbconnect($dbconn);
	if ($mysqli == false) {
		// return an error message
		error_log("get_role_menu(): dbconnect()==" . $mysqli->errno, 0);
		return false;
	}
	
	// get the menu_code string for the role_id
	$strsql = "SELECT DISTINCT role_menu_menu_codes FROM tbl_role_menu " .
			"WHERE (role_menu_mark_as_deleted IS NULL OR role_menu_mark_as_deleted = 0) AND role_menu_role_id = ?";
	
	//error_log("SQL=" . $strsql, 0);
	
	$stmt = $mysqli->prepare($strsql);
	
	$stmt->bind_param("i", $role_id);
	
	$stmt->execute();
	
	if ($stmt->errno <> 0) {

		error_log("get_role_menu():  stmt->errno==" . $stmt->errno, 0);
		$stmt->close();
		$mysqli->close();
		return false;
	
	}
	
	$stmt->bind_result($role_menu_codes);
	
	while ($stmt->fetch()) {
		$str_menu_codes = $role_menu_codes;
	}
	
	if ($str_menu_codes==NULL) {

		$stmt->close();
		$mysqli->close();
		error_log("get_role_menu():  str_menu_codes==NULL", 0);
		return false;
	
	} else {
	
		error_log("get_role_menu(): strcodes==" . $str_menu_codes, 0);
		$stmt->close();
		$mysqli->close();
		return $str_menu_codes;

	}
}

?>
