<?php

function get_list_branch($dbconn) {
	

	// return all roles except those marked as deleted
	$strsql = "SELECT DISTINCT company_id, branch_id, branch_name " .
			"FROM tbl_branch WHERE branch_mark_as_deleted IS NULL";

	$mysqli = dbconnect($dbconn);
	
	$stmt = $mysqli->prepare($strsql);
	
	$stmt->execute();
	
	$stmt->bind_result($company_id, $branch_id, $branch_name);
	
	while($stmt->fetch()) {
		$resultarray[] = array('company_id' => $company_id, 'branch_id' => $branch_id, 'branch_name' => $branch_name);
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
