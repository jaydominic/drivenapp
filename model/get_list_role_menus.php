<?php

function get_list_role_menus($dbconn) {

	$mysqli = dbconnect($dbconn);

	$strsql = "SELECT role_menu_id, role_menu_role_id, role_menu_menu_codes, role_menu_mark_as_deleted FROM tbl_role_menu ORDER BY role_menu_id";

	$stmt = $mysqli->prepare($strsql);

	$stmt->execute();

	$stmt->bind_result($role_menu_id, $role_menu_role_id, $role_menu_menu_codes, $menu_mark_as_deleted);

	while($stmt->fetch()) {
		$resultarray[] = array('role_menu_id' => $role_menu_id, 'role_menu_role_id' => $role_menu_role_id, 'role_menu_menu_codes' => $role_menu_menu_codes, 'menu_mark_as_deleted' => $menu_mark_as_deleted);
	}

	$stmt->close();
	$mysqli->close();

	if (!$resultarray) {
		return false;
	} else {
		return $resultarray;
	}

}

?>
