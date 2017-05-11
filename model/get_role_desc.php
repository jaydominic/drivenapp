<?php

function get_role_desc($dbconn, $login_id) {

	$strsql = "SELECT tbl_role.role_description FROM tbl_role " . 
			"INNER JOIN tbl_login ON (tbl_role.role_id = tbl_login.login_role_id AND tbl_login.login_id = ?)";
	
	$mysqli = dbconnect($dbconn);
	
	$stmt = $mysqli->prepare($strsql);
	
	$stmt->bind_param("i", $login_id);
	
	$stmt->execute();
	
	$stmt->bind_result($role_description);
	
	while($stmt->fetch()) {
		$rd = $role_description;
	}
	
	$stmt->close();
	$mysqli->close();
	
	if (!isset($rd)) {
		return false;
	} else {
		return $rd;
	}
	
}

?>
