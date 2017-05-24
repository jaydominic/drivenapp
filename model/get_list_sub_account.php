<?php

/*
 * This function will return an array object containing the following fields:
 * 
 * coa_sub_account
 * coa_parent_account
 * coa_account_no
 * coa_group
 * coa_type
 */

function get_list_sub_account($dbconn, $parent_account = NULL) {
	
	$mysqli = dbconnect($dbconn);
	
	if ($parent_account != NULL) {
	
		$strsql = "SELECT chart_of_account_id, chart_of_account_sub_account, chart_of_account_parent_account, " . 
			"chart_of_account_account_no, chart_of_account_group, chart_of_account_type " .
			"FROM tbl_chart_of_account USE INDEX (sub_account) " .
			"WHERE chart_of_account_parent_account = ? AND chart_of_account_is_active = 'Y' " .
			"AND chart_of_account_mark_as_deleted IS NULL " .
			"ORDER BY chart_of_account_sub_account";
	
		$stmt = $mysqli->prepare($strsql);
	
		$stmt->bind_param("s", $parent_account);
	
	} else {
		
		$strsql = "SELECT chart_of_account_id, chart_of_account_sub_account, chart_of_account_parent_account, " .
			"chart_of_account_account_no, chart_of_account_group, chart_of_account_type " .
			"FROM tbl_chart_of_account USE INDEX (sub_account) " .
			"WHERE chart_of_account_is_active = 'Y' AND chart_of_account_mark_as_deleted IS NULL " .
			"ORDER BY chart_of_account_sub_account";
		
		$stmt = $mysqli->prepare($strsql);
	
	}
	
	error_log("get_list_sub_account.php:  strsql=" . $strsql, 0);
	
	$stmt->execute();
	
	$stmt->bind_result($coa_id, $coa_sub_acct, $coa_parent_acct, $coa_acct_no, $coa_group, $coa_type);
	
	while($stmt->fetch()) {
		$resultarray[] = array('coa_id' => $coa_id,
							'coa_sub_account' => $coa_sub_acct,
							'coa_parent_account' => $coa_parent_acct,
							'coa_account_no' => $coa_acct_no,
							'coa_group' => $coa_group,
							'coa_type' => $coa_type);
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
