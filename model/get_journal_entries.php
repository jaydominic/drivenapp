<?php

/*
 * This function will return an array object containing the following fields:
 * 
 * journal_row_id
 * journal_ref_id
 * journal_entry_date
 * journal_period
 * journal_posting_date
 * journal_txn_type
 * journal_txn_class
 * journal_description
 * journal_total_credit_amount
 * journal_total_credit_wtax
 * journal_total_credit_vat
 * journal_total_credit_net
 * journal_total_debit_amount
 * journal_total_debit_wtax
 * journal_total_debit_vat
 * journal_total_debit_net
 * journal_mark_as_void
 * journal_mark_as_deleted
 * journal_created_by_login_id
 * journal_created_ts
 * journal_modified_by_login_id
 * journal_modified_ts
 * 
 */

function get_journal_entries($dbconn, $ref_id = NULL, $livesearch = NULL) {
	
	$thisPage = basename(__FILE__);
	
	$mysqli = dbconnect($dbconn);
	
	error_log($thisPage . ":  ref_id=" . $ref_id, 0);
	
	if ($ref_id != NULL) {
	
		$strsql = "SELECT journal_row_id, journal_ref_id, journal_entry_date, journal_period,
			journal_posting_date, journal_txn_type, journal_txn_class, journal_description,
			journal_total_credit_amount, journal_total_credit_wtax, journal_total_credit_vat, 
			journal_total_credit_net, journal_total_debit_amount, journal_total_debit_wtax, 
			journal_total_debit_vat, journal_total_debit_net, journal_mark_as_void, 
			journal_mark_as_deleted, journal_created_by_login_id, journal_created_ts, 
			journal_modified_by_login_id, journal_modified_ts
			FROM tbl_journal_entry_header
			USE INDEX (journal_ref_id) 
			WHERE journal_ref_id = ? AND journal_mark_as_deleted IS NULL
			ORDER BY journal_ref_id";
	
		$stmt = $mysqli->prepare($strsql);
	
		$stmt->bind_param("s", $ref_id);
	
	} else {  // ref_id has no value
		
		if (isset($livesearch)) {  // livesearch provided
			
			$strsql = "SELECT journal_row_id, journal_ref_id, journal_entry_date, journal_period,
				journal_posting_date, journal_txn_type, journal_txn_class, journal_description,
				journal_total_credit_amount, journal_total_credit_wtax, journal_total_credit_vat, 
				journal_total_credit_net, journal_total_debit_amount, journal_total_debit_wtax, 
				journal_total_debit_vat, journal_total_debit_net, journal_mark_as_void, 
				journal_mark_as_deleted, journal_created_by_login_id, journal_created_ts, 
				journal_modified_by_login_id, journal_modified_ts
				FROM tbl_journal_entry_header
				USE INDEX (journal_live_search)
				WHERE journal_mark_as_deleted IS NULL
				AND journal_ref_id LIKE '" . trim($livesearch). "%' OR
				journal_entry_date LIKE '" . trim($livesearch). "%' OR
				journal_period LIKE '" . trim($livesearch). "%' OR
				journal_posting_date LIKE '" . trim($livesearch). "%' OR
				journal_txn_type LIKE '" . trim($livesearch). "%' OR
				journal_txn_class LIKE '" . trim($livesearch). "%' OR
				journal_description LIKE '%" . trim($livesearch). "%' 
				ORDER BY journal_ref_id";
			
		} else { // no search parameters

			$strsql = "SELECT journal_row_id, journal_ref_id, journal_entry_date, journal_period,
				journal_posting_date, journal_txn_type, journal_txn_class, journal_description,
				journal_total_credit_amount, journal_total_credit_wtax, journal_total_credit_vat, 
				journal_total_credit_net, journal_total_debit_amount, journal_total_debit_wtax, 
				journal_total_debit_vat, journal_total_debit_net, journal_mark_as_void, 
				journal_mark_as_deleted, journal_created_by_login_id, journal_created_ts, 
				journal_modified_by_login_id, journal_modified_ts
				FROM tbl_journal_entry_header
				USE INDEX (journal_ref_id)
				WHERE journal_mark_as_deleted IS NULL
				ORDER BY journal_ref_id";
		
		}
		
		$stmt = $mysqli->prepare($strsql);
	
	}
	
	error_log($thisPage . ": strsql=" . $strsql, 0);
	
	$stmt->execute();
	
	$stmt->bind_result($journal_row_id, $journal_ref_id, $journal_entry_date, $journal_period,
			$journal_posting_date, $journal_txn_type, $journal_txn_class, $journal_description,
			$journal_total_credit_amount, $journal_total_credit_wtax, $journal_total_credit_vat, 
			$journal_total_credit_net, $journal_total_debit_amount, $journal_total_debit_wtax, 
			$journal_total_debit_vat, $journal_total_debit_net, $journal_mark_as_void, 
			$journal_mark_as_deleted, $journal_created_by_login_id, $journal_created_ts, 
			$journal_modified_by_login_id, $journal_modified_ts);
	
	while($stmt->fetch()) {
		$resultarray[] = array('journal_row_id' => $journal_row_id, 
			'journal_ref_id' => $journal_ref_id, 
			'journal_entry_date' => $journal_entry_date, 
			'journal_period' => $journal_period,
			'journal_posting_date' => $journal_posting_date, 
			'journal_txn_type' => $journal_txn_type, 
			'journal_txn_class' => $journal_txn_class, 
			'journal_description' => $journal_description,
			'journal_total_credit_amount' => $journal_total_credit_amount, 
			'journal_total_credit_wtax' => $journal_total_credit_wtax, 
			'journal_total_credit_vat' => $journal_total_credit_vat,
			'journal_total_credit_net' => $journal_total_credit_net,
			'journal_total_debit_amount' => $journal_total_debit_amount, 
			'journal_total_debit_wtax' => $journal_total_debit_wtax, 
			'journal_total_debit_vat' => $journal_total_debit_vat,
			'journal_total_debit_net' => $journal_total_debit_net,
			'journal_mark_as_void' => $journal_mark_as_void, 
			'journal_mark_as_deleted' => $journal_mark_as_deleted, 
			'journal_created_by_login_id' => $journal_created_by_login_id,
			'journal_created_ts' => $journal_created_ts, 
			'journal_modified_by_login_id' => $journal_modified_by_login_id, 
			'journal_modified_ts' => $journal_modified_ts);
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

