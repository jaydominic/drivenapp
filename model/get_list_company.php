<?php

function get_list_company($dbconn) {
	
	// return all roles except those marked as deleted
	$strsql = "SELECT DISTINCT company_id, company_name " .
			"FROM tbl_company WHERE company_mark_as_deleted IS NULL";

	$mysqli = dbconnect($dbconn);
	
	$stmt = $mysqli->prepare($strsql);
	
	$stmt->execute();
	
	$stmt->bind_result($company_id, $company_name);
	
	while($stmt->fetch()) {
		$resultarray[] = array('company_id' => $company_id, 'company_name' => $company_name);
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
