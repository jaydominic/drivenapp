<?php

function get_list_menus($dbconn, $include_deleted = false, $role_id = NULL) {

	$mysqli = dbconnect($dbconn);

	if ($include_deleted == false) {
		// only get menu records that are not marked as deleted
		$strsql = "SELECT menu_id, menu_code, menu_description, menu_enabled, menu_mark_as_deleted " .
				"FROM tbl_menu WHERE menu_mark_as_deleted IS NULL ORDER BY menu_code";
	} else { 
		// get all menu records even those marked as deleted
		$strsql = "SELECT menu_id, menu_code, menu_description, menu_enabled, menu_mark_as_deleted " .
				"FROM tbl_menu ORDER BY menu_code";
	}
	
	$stmt = $mysqli->prepare($strsql);

	$stmt->execute();

	$stmt->bind_result($menu_id, $menu_code, $menu_description, $menu_enabled, $menu_mark_as_deleted);

	while($stmt->fetch()) {
		$resultarray[] = array('menu_id' => $menu_id, 'menu_code' => $menu_code, 'menu_description' => $menu_description, 'menu_enabled' => $menu_enabled, 'menu_mark_as_deleted' => $menu_mark_as_deleted);
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