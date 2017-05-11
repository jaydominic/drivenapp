<?php

/*

tbl_menu structure:

column indexes
==============
0 - menu_id
1 - menu_code
2 - menu_description
3 - menu_enabled
4 - menu_group_id
5 - menu_group_class
6 - menu_row_id
7 - menu_filename
8 - menu_column
9 - menu_mark_as_deleted
10 - menu_modified_by_login_id
11 - menu_modified_ts

*/

function getmenu($dbconn, $role_id) {
	
	error_log("get_menu():  role_id=" . $role_id, 0);
	
	// navigate folders based on the location of the currently executing PHP file in the browser
	// in this case it is:  http://localhost/driven-group.com/views/home.php
	
	$mysqli = dbconnect($dbconn);
	if ($mysqli == false) {
		// return an error message
		error_log("get_user(): dbconnect()==" . $mysqli->errno, 0);
		return false;
	}
	
	// get all the menu data
	$strsql = "SELECT menu_id, menu_code, menu_description, menu_enabled, menu_group_class, menu_filename, menu_column, menu_row_id " .
			"FROM tbl_menu WHERE menu_mark_as_deleted IS NULL " .
			"ORDER BY menu_column, menu_row_id";
	error_log("get_menu(): SQL=" . $strsql, 0);
	
	$stmt = $mysqli->prepare($strsql);

	$stmt->execute();
	
	$stmt->bind_result($menu_id, $menu_code, $menu_description, $menu_enabled, $menu_group_class, $menu_filename, $menu_column, $menu_row_id);
	
	//fetch the rows
	while ($stmt->fetch()) {
		$resultarray[] = array('menu_id' => $menu_id, 'menu_code' => $menu_code, 'menu_description' => $menu_description, 'menu_enabled' => $menu_enabled, 'menu_group_class' => $menu_group_class, 'menu_filename' => $menu_filename, 'menu_column' => $menu_column, 'menu_row_id' => $menu_row_id);
	}
	
	//var_dump($resultarray);
	
	$stmt->close();
	$mysqli->close();
	
	$jsonstr = json_encode($resultarray);
	
	return $jsonstr;

}

?>
