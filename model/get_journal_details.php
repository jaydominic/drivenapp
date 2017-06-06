<?php

/*
 * This function will return an array object containing the following fields:
 * 
 * journal_details_row_id
 * journal_details_ref_id
 * journal_detaila_rec_type
 * journal_details_coa_id
 * journal_details_parent_acct
 * journal_details_sub_acct
 * journal_details_desc
 * journal_details_amount
 * journal_details_wtax
 * journal_details_wtax_amount
 * journal_details_vat
 * journal_details_vat_amount
 * journal_details_net_amount
 * journal_details_ref_doc
 * journal_details_mark_as_deleted
 * journal_details_created_by_login_id
 * journal_details_created_ts
 * journal_details_modified_by_login_id
 * journal_details_modified_ts
 * 
 */

function get_journal_details($dbconn, $ref_id = NULL, $rec_type = NULL, $livesearch = NULL) {
	
	$thisPage = basename(__FILE__);
	
	$mysqli = dbconnect($dbconn);
	
	error_log($thisPage . ":  ref_id=" . $ref_id, 0);
	
	if ($ref_id != NULL) {
	
		if ($rec_type == NULL) {
			$strsql = "SELECT journal_details_row_id, journal_details_ref_id, journal_details_rec_type, " .
				"journal_details_coa_id, journal_details_parent_acct, journal_details_sub_acct, journal_details_desc, " .
				"journal_details_amount, journal_details_wtax, journal_details_wtax_amount, journal_details_vat, " .
				"journal_details_vat_amount, journal_details_net_amount, journal_details_ref_doc, " .
				"journal_details_mark_as_deleted, journal_details_created_by_login_id, journal_details_created_ts, " .
				"journal_details_modified_by_login_id, journal_details_modified_ts " .
				"FROM tbl_journal_entry_details " .
				"USE INDEX (journal_details_ref_id) " . 
				"WHERE journal_details_ref_id = ? AND journal_details_mark_as_deleted IS NULL " .
				"ORDER BY journal_details_ref_id, journal_details_rec_type";

		} else if ($rec_type == "DEBIT") {
			
			$strsql = "SELECT journal_details_row_id, journal_details_ref_id, journal_details_rec_type, " .
				"journal_details_coa_id, journal_details_parent_acct, journal_details_sub_acct, journal_details_desc, " .
				"journal_details_amount, journal_details_wtax, journal_details_wtax_amount, journal_details_vat, " .
				"journal_details_vat_amount, journal_details_net_amount, journal_details_ref_doc, " .
				"journal_details_mark_as_deleted, journal_details_created_by_login_id, journal_details_created_ts, " .
				"journal_details_modified_by_login_id, journal_details_modified_ts " .
				"FROM tbl_journal_entry_details " .
				"USE INDEX (journal_details_ref_id) " .
				"WHERE journal_details_ref_id = ? AND journal_details_mark_as_deleted IS NULL AND journal_details_rec_type = 'DEBIT' " .
				"ORDER BY journal_details_ref_id, journal_details_rec_type";
		
		} else { // assume CREDIT
		
			$strsql = "SELECT journal_details_row_id, journal_details_ref_id, journal_details_rec_type, " .
				"journal_details_coa_id, journal_details_parent_acct, journal_details_sub_acct, journal_details_desc, " .
				"journal_details_amount, journal_details_wtax, journal_details_wtax_amount, journal_details_vat, " .
				"journal_details_vat_amount, journal_details_net_amount, journal_details_ref_doc, " .
				"journal_details_mark_as_deleted, journal_details_created_by_login_id, journal_details_created_ts, " .
				"journal_details_modified_by_login_id, journal_details_modified_ts " .
				"FROM tbl_journal_entry_details " .
				"USE INDEX (journal_details_ref_id) " .
				"WHERE journal_details_ref_id = ? AND journal_details_mark_as_deleted IS NULL AND journal_details_rec_type = 'CREDIT' " .
				"ORDER BY journal_details_ref_id, journal_details_rec_type";
		
		}
		
		error_log($thisPage . ": strsql to use=" . $strsql, 0);
		
		$stmt = $mysqli->prepare($strsql);
	
		$stmt->bind_param("s", $ref_id);
	
		error_log($thisPage . ": bind_param()=" . $stmt->errno, 0);
		
	} else {  // ref_id has no value
		
		if (isset($livesearch)) {  // livesearch provided
			
			$strsql = "SELECT journal_details_row_id, journal_details_ref_id, journal_details_rec_type, " .
				"journal_details_coa_id, journal_details_parent_acct, journal_details_sub_acct, journal_details_desc, " .
				"journal_details_amount, journal_details_wtax, journal_details_wtax_amount, journal_details_vat, " . 
				"journal_details_vat_amount, journal_details_net_amount, journal_details_ref_doc, " .
				"journal_details_mark_as_deleted, journal_details_created_by_login_id, journal_details_created_ts, " .
				"journal_details_modified_by_login_id, journal_details_modified_ts " .
				"FROM tbl_journal_entry_details " .
				"USE INDEX (journal_details_live_search) " .
				"WHERE journal_mark_as_deleted IS NULL " .
				"AND (journal_details_ref_id LIKE '" . trim($livesearch). "%' OR " .
				"journal_details_rec_type LIKE '" . trim($livesearch). "%' OR " .
				"journal_details_coa_id LIKE '" . trim($livesearch). "%' OR " .
				"journal_details_parent_acct LIKE '" . trim($livesearch). "%' OR " .
				"journal_details_sub_acct LIKE '" . trim($livesearch). "%' OR " .
				"journal_details_desc LIKE '" . trim($livesearch). "%' OR " .
				"journal_details_ref_doc LIKE '%" . trim($livesearch). "%') " .
				"ORDER BY journal_details_ref_id, journal_details_rec_type";
			
		} else { // no search parameters

			$strsql = "SELECT journal_details_row_id, journal_details_ref_id, journal_details_rec_type, " .
				"journal_details_coa_id, journal_details_parent_acct, journal_details_sub_acct, journal_details_desc, " .
				"journal_details_amount, journal_details_wtax, journal_details_wtax_amount, journal_details_vat, " .
				"journal_details_vat_amount, journal_details_net_amount, journal_details_ref_doc, " .
				"journal_details_mark_as_deleted, journal_details_created_by_login_id, journal_details_created_ts, " .
				"journal_details_modified_by_login_id, journal_details_modified_ts " .
				"FROM tbl_journal_entry_details " .
				"USE INDEX (journal_details_ref_id) " .
				"WHERE journal_details_mark_as_deleted IS NULL " .
				"ORDER BY journal_details_ref_id, journal_details_rec_type";
			
		}
		
		$stmt = $mysqli->prepare($strsql);
	
	}
	
	error_log($thisPage . ": strsql=" . $strsql, 0);
	
	$stmt->execute();
	
	$stmt->bind_result($journal_details_row_id, $journal_details_ref_id, $journal_details_rec_type,
			$journal_details_coa_id, $journal_details_parent_acct, $journal_details_sub_acct, $journal_details_desc,
			$journal_details_amount, $journal_details_wtax, $journal_details_wtax_amount, $journal_details_vat,
			$journal_details_vat_amount, $journal_details_net_amount, $journal_details_ref_doc,
			$journal_details_mark_as_deleted, $journal_details_created_by_login_id, $journal_details_created_ts,
			$journal_details_modified_by_login_id, $journal_details_modified_ts);
	
	while($stmt->fetch()) {
		$resultarray[] = array('journal_details_row_id' => $journal_details_row_id, 
				'journal_details_ref_id' => $journal_details_ref_id, 
				'journal_details_rec_type' => $journal_details_rec_type, 
				'journal_details_coa_id' => $journal_details_coa_id,
				'journal_details_parent_acct' => $journal_details_parent_acct, 
				'journal_details_sub_acct' => $journal_details_sub_acct, 
				'journal_details_desc' => $journal_details_desc, 
				'journal_details_amount' => $journal_details_amount,
				'journal_details_wtax' => $journal_details_wtax, 
				'journal_details_wtax_amount' => $journal_details_wtax_amount, 
				'journal_details_vat' => $journal_details_vat,
				'journal_details_vat_amount' => $journal_details_vat_amount, 
				'journal_details_net_amount' => $journal_details_net_amount, 
				'journal_details_ref_doc' => $journal_details_ref_doc,
				'journal_details_mark_as_deleted' => $journal_details_mark_as_deleted, 
				'journal_details_created_by_login_id' => $journal_details_created_by_login_id, 
				'journal_details_created_ts' => $journal_details_created_ts,
				'journal_details_modified_by_login_id' => $journal_details_modified_by_login_id, 
				'journal_details_modified_ts' => $journal_details_modified_ts);
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

