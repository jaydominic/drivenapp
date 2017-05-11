<?php

function update_password($dbconn, $login_id, $target_user_id, $target_user_password) {
	
	error_log("update_password.php:  START", 0);
	
	// this only updates the password of EXISTING user accounts
	
	$strsql = "UPDATE tbl_login SET login_password = ?, login_modified_by_login_id = ?, login_modified_ts = NOW() WHERE login_id = ?";
	
	$mysqli = dbconnect($dbconn);
	
	$stmt = $mysqli->prepare($strsql);
	if ($stmt->errno != 0) {
		error_log("update_password.php: Error at prepar(). ERROR " . $stmt->errno, 0);
		return false;
	}
	
	$stmt->bind_param("sii", $target_user_password, $login_id, $target_user_id);
	if ($stmt->errno != 0) {
		error_log("update_password.php: Error at bind_param(). ERROR " . $stmt->errno, 0);
		return false;
	}
	
	$stmt->execute();
	
	$numrows = $mysqli->affected_rows;
	if ($numrows == 0) {
		error_log("update_password.php:  No rows updated for login_id ", $target_user_id, 0);
		$stmt->close();
		$mysqli->close();
		return false;
	} else {
		$stmt->close();
		$mysqli->close();
	}
	
	if (!isset($resultarray)) {
		return false;
	} else {
		return $resultarray;
	}

}

?>

