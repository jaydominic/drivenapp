<?php

/*
 * This function will return an array object containing the following fields:
 * 
 */

function get_list_parent_account($dbconn) {
	
	$thisPage = "get_list_parent_account.php";
	
	// get the chart of accounts
	$strsql = "SELECT DISTINCT chart_of_account_parent_account " .
			"FROM tbl_chart_of_account USE INDEX (parent_sub_account) " . 
			"WHERE chart_of_account_is_active = 'Y' AND chart_of_account_mark_as_deleted IS NULL " .
			"ORDER BY chart_of_account_parent_account";

	$mysqli = dbconnect($dbconn);
	
	$stmt = $mysqli->prepare($strsql);
	if($mysqli->errno != 0) {
		error_log($thisPage . ": prepare() error " . $mysqli->errno, 0);
	}
	
	$stmt->execute();
	if($stmt->errno != 0) {
		error_log($thisPage . ": execute() error " . $stmt->errno, 0);
	}
	
	$stmt->bind_result($chart_of_account_parent_account);
	if($stmt->errno != 0) {
		error_log($thisPage . ": bind_result() error " . $stmt->errno, 0);
	}
	
	while($stmt->fetch()) {
		$resultarray[] = array('chart_of_account_parent_account' => $chart_of_account_parent_account);
	}
	
	$stmt->close();
	$mysqli->close();
	
	if (!isset($resultarray)) {
		return false;
	} else {
		return $resultarray;
	}
	
}

?>
