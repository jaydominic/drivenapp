<?php

function get_fullname($login_id, $dbconn) {

	$mysqli = dbconnect($dbconn);
	
	$strsql = "SELECT login_firstname, login_lastname FROM tbl_login WHERE login_id = ?";
	
	$stmt = $mysqli->prepare($strsql);
	
	$stmt->bind_param("i", $login_id);
	
	$stmt->execute();
	
	$stmt->bind_result($login_firstname, $login_lastname);
	
	while($stmt->fetch()) {
		$fullname = $login_firstname . " " . $login_lastname;
	}
	
	$stmt->close();
	$mysqli->close();
	
	if (!$fullname) {
		return false;
	} else {
		return $fullname;
	}
	
}

?>
