<?php
require_once '../model/constants.php';
require_once '../model/dbconnect.php';

$uid = $_REQUEST["login_id"];
$sid = $_REQUEST["session_id"];

// log off the user from tbl_login
$strsql = "UPDATE tbl_login SET login_is_active='N', login_modified_by_login_id = ?, login_modified_ts=NOW() ".
		"WHERE login_id = ?";

$mysqli = dbconnect($dbconn);
$stmt = $mysqli->prepare($strsql);
if ($mysqli->errno <> 0) {
	error_log("logoff.php:  Error encountered in prepare(tbl_login) -> mysqli error: " . $mysqli->errno, 0);
}

$stmt->bind_param("ii", $uid, $uid);
if ($stmt->errno <> 0) {
	error_log("logoff.php:  Error encountered in bind_param(tbl_login) -> stmt error: " . $stmt->errno, 0);
}

$stmt->execute();
if ($stmt->errno <> 0) {
	error_log("logoff.php:  Error encountered in execute(tbl_login) -> stmt error: " . $stmt->errno, 0);
}

$stmt->close();
$mysqli->close();


// update the session data table
$strsql = "UPDATE tbl_login_session SET login_session_closed_ts=NOW() ".
		"WHERE login_session_session_id = ?";

$mysqli = dbconnect($dbconn);
$stmt = $mysqli->prepare($strsql);
if ($mysqli->errno <> 0) {
	error_log("logoff.php:  Error encountered in prepare(tbl_login_session) -> mysqli error: " . $mysqli->errno, 0);
}

$stmt->bind_param("s", $sid);
if ($stmt->errno <> 0) {
	error_log("logoff.php:  Error encountered in bind_param(tbl_login_session) -> stmt error: " . $stmt->errno, 0);
}

$stmt->execute();
if ($stmt->errno <> 0) {
	error_log("logoff.php:  Error encountered in execute(tbl_login_session) -> stmt error: " . $stmt->errno, 0);
}

$stmt->close();
$mysqli->close();

session_unset();
session_destroy();
?>
<script type="text/javascript">window.location.href="../index.php"</script>
