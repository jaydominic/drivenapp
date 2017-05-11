<?php

function log_session($dbconn, $uname, $session_id) {

	error_log("log_session():  START", 0);
	
	$mysqli = dbconnect($dbconn);
	if ($mysqli == false) {
		// return an error message
		error_log("log_session(): dbconnect()==" . $mysqli->errno, 0);
		return false;
	}
	
	$strsql = "INSERT INTO tbl_login_session (login_session_login_username, login_session_session_id, " .
			"login_session_remote_ip, login_session_created_ts) VALUES (?, ?, ?, NOW())";
	
	error_log("log_session(): SQL=" . $strsql, 0); // update logfile
	
	$stmt = $mysqli->prepare($strsql);
	
	$stmt->bind_param("sss", $un, $sid, $rem_ip);
	
	$un = $uname;
	$sid = $session_id;
	$rem_ip = $_SERVER['REMOTE_ADDR'];
	
	$stmt->execute();
	
	$stmt->close();
	$mysqli->close();
	
	error_log("log_session():  END", 0);
}

?>
