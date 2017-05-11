<?php

function get_session_status($dbconn, $login_username, $session_id) {
	

	// return all roles except those marked as deleted
	$strsql = "SELECT COUNT(login_session_id) AS open_sessions " .
			"FROM tbl_login_session WHERE login_session_login_username = ? " .
			"AND login_session_session_id = ? AND login_session_closed_ts IS NULL";

	$mysqli = dbconnect($dbconn);
	
	$stmt = $mysqli->prepare($strsql);
	
	$stmt->bind_param("ss", $login_username, $session_id);
	
	$stmt->execute();
	
	$stmt->bind_result($open_sessions);
	
	while($stmt->fetch()) {
		$resultarray = array('open_sessions' => $open_sessions);
	}
	
	$stmt->close();
	$mysqli->close();
	
	if (!isset($resultarray)) {
		return false;
	} else {
		return $resultarray['open_sessions'];
	}
	
}

?>
