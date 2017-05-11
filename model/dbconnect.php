<?php

function dbconnect($dbconn) {

	$mysqli = new mysqli($dbconn['dbhost'], $dbconn['dbuser'], $dbconn['dbpass'], $dbconn['dbname'], $dbconn['dbport']);

	if ($mysqli->connect_errno) {
		error_log("dbconnect(): MySQL connect error no. " . $mysqli->connect_errno, 0);
		return false;  //something went wrong while trying to connect
	} else {
		return $mysqli; //successful connection!
	}

}

?>
