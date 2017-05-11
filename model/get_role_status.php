<?php

function get_role_status($dbconn, $role_id) {

	$mysqli = dbconnect($dbconn);

	$strsql = "SELECT DISTINCT role_is_active FROM tbl_role WHERE role_id = ?";

	$stmt = $mysqli->prepare($strsql);

	$stmt->bind_param("i", $role_id);

	$stmt->execute();

	$stmt->bind_result($role_is_active);

	while($stmt->fetch()) {
		$ria = $role_is_active;
	}

	$stmt->close();
	$mysqli->close();

	if (!isset($ria)) {
		return false;
	} else {
		return $ria;
	}

}

?>
