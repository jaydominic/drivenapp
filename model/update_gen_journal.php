<?php

/* 
	$journalheader = array('journal_ref_id' => $_REQUEST['journal_reference_id'], 
					'journal_entry_date' => $journal_entry_date,
					'journal_period' => $journal_period, 
					'journal_posting_date' => $journal_posting_date,
					'journal_txn_type' => $_REQUEST['journal_txn_type'], 
					'journal_gen_desc' => $_REQUEST['journal_gen_desc'],
					'journal_txn_class' => $_REQUEST['journal_txn_class'],
					'journal_dr_total_amt' => $_REQUEST['dr_total_amt'],
					'journal_dr_total_wtax' => $_REQUEST['dr_total_wtax'],
					'journal_dr_total_vat' => $_REQUEST['dr_total_vat'],
					'journal_dr_total_net' => $_REQUEST['dr_total_net'],
					'journal_cr_total_amt' => $_REQUEST['cr_total_amt'],
					'journal_cr_total_wtax' => $_REQUEST['cr_total_wtax'],
					'journal_cr_total_vat' => $_REQUEST['cr_total_vat'],
					'journal_cr_total_net' => $_REQUEST['cr_total_net'],
					'journal_credit_rows' => $_REQUEST['creditRowCtr'],
					'journal_debit_rows' => $_REQUEST['debitRowCtr']);

	$creditdata = array('cr_parent_acct' => $_REQUEST['cr_parent_acct'], 
					'cr_sub_acct' => $_REQUEST['cr_sub_acct'],
					'cr_coa_id' => $_REQUEST['cr_coa_id'],
					'cr_jnl_details' => $_REQUEST['cr_jnl_details'], 
					'cr_jnl_amt' => $_REQUEST['cr_jnl_amt'],
					'cr_jnl_wtax_type_opt' => $_REQUEST['cr_jnl_wtax_type_opt'], 
					'cr_jnl_wtax' => $_REQUEST['cr_jnl_wtax'],
					'cr_jnl_vat_type_opt' => $_REQUEST['cr_jnl_vat_type_opt'], 
					'cr_jnl_vat' => $_REQUEST['cr_jnl_vat'],
					'cr_jnl_net' => $_REQUEST['cr_jnl_net'], 
					'cr_jnl_ref_doc_opt' => $_REQUEST['cr_jnl_ref_doc_opt']);
	
	$debitdata = array('dr_parent_acct' => $_REQUEST['dr_parent_acct'],
					'dr_sub_acct' => $_REQUEST['dr_sub_acct'],
					'dr_coa_id' => $_REQUEST['dr_coa_id'],
					'dr_jnl_details' => $_REQUEST['dr_jnl_details'],
					'dr_jnl_amt' => $_REQUEST['dr_jnl_amt'],
					'dr_jnl_wtax_type_opt' => $_REQUEST['dr_jnl_wtax_type_opt'],
					'dr_jnl_wtax' => $_REQUEST['dr_jnl_wtax'],
					'dr_jnl_vat_type_opt' => $_REQUEST['dr_jnl_vat_type_opt'],
					'dr_jnl_vat' => $_REQUEST['dr_jnl_vat'],
					'dr_jnl_net' => $_REQUEST['dr_jnl_net'],
					'dr_jnl_ref_doc_opt' => $_REQUEST['dr_jnl_ref_doc_opt']);
*/

function update_gen_journal($dbconn, $operation, $login_id, $journalheader, $creditdata, $debitdata) {
	
	$thisPage = basename(__FILE__);
	
	error_log($thisPage . ":  START", 0);
	
	// all parameters are required, check first if any are missing / null
	// error_log("update_user_acct.php:  checking parameter [dbconn]", 0);
	if (!isset($dbconn)) {
		error_log($thisPage . ":  ERROR -> Parameter [dbconn] contains no data", 0);
		return false;
	}
	if (!isset($operation)) {
		error_log($thisPage . ":  ERROR -> Parameter [operation] contains no data", 0);
		return false;
	}
	if (!isset($login_id)) {
		error_log($thisPage . ":  ERROR -> Parameter [login_id] contains no data", 0);
		return false;
	}
	if (!isset($journalheader)) {
		error_log($thisPage . ":  ERROR -> Parameter [journalheader] contains no data", 0);
		return false;
	}
	if (!isset($creditdata)) {
		error_log($thisPage . ":  ERROR -> Parameter [creditdata] contains no data", 0);
		return false;
	}
	if (!isset($debitdata)) {
		error_log($thisPage . ":  ERROR -> Parameter [debitdata] contains no data", 0);
		return false;
	}
	
	//====================================================================================================
	// check the type of operation being requested
	if ($operation == "INSERT") {
		
		// insert the journal header row into tbl_journal_entry_header
		
		$mysqli = dbconnect($dbconn);
		
		$strsql = "INSERT INTO tbl_journal_entry_header(journal_ref_id,	journal_entry_date,	journal_period,
				journal_posting_date, journal_txn_type, journal_txn_class, journal_description, 
				journal_total_credit_amount, journal_total_credit_wtax, journal_total_credit_vat, journal_total_credit_net,
				journal_total_debit_amount, journal_total_debit_wtax, journal_total_debit_vat, journal_total_debit_net,
				journal_created_by_login_id, journal_created_ts) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
		
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno <> 0) {
			error_log($thisPage . ": Error at mysqli->prepare(INSERT-journalheader) -> ERROR: " . $mysqli->error, 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}
		
		// make sure all ref ID's are all uppercase
		$journal_ref_id = strtoupper($journalheader['journal_ref_id']);
		
		$stmt->bind_param("sssssssddddddddi", $journal_ref_id, $journalheader['journal_entry_date'], 
				$journalheader['journal_period'], $journalheader['journal_posting_date'], 
				$journalheader['journal_txn_type'], $journalheader['journal_txn_class'], 
				$journalheader['journal_gen_desc'], $journalheader['journal_cr_total_amt'], 
				$journalheader['journal_cr_total_wtax'], $journalheader['journal_cr_total_vat'], 
				$journalheader['journal_cr_total_net'], $journalheader['journal_dr_total_amt'], 
				$journalheader['journal_dr_total_wtax'], $journalheader['journal_dr_total_vat'], 
				$journalheader['journal_dr_total_net'],	$login_id);

		if ($stmt->errno <> 0) {
			error_log($thisPage . ": Error at stmt->bind_param(INSERT-journalheader) -> ERROR: " . $stmt->error, 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}
		
		$stmt->execute();
		
		error_log($thisPage . "->A", 0);
		if ($mysqli->errno != 0) {
			error_log($thisPage . ": Error stmt->execute(INSERT-journalheader)-> ERROR No. " . $mysqli->errno, 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}
		error_log($thisPage . "->B", 0);
		// $stmt->close();
		
		// ==========================================================================================================
		// insert the credit data row(s) into tbl_journal_entry_details with journal_details_rec_type = 'CREDIT'
		
		$strsql = "INSERT INTO tbl_journal_entry_details(journal_details_ref_id, journal_details_rec_type,
				journal_details_coa_id, journal_details_parent_acct, journal_details_sub_acct, journal_details_desc,
				journal_details_amount, journal_details_wtax, journal_details_wtax_amount, journal_details_vat,
				journal_details_vat_amount, journal_details_net_amount, journal_details_ref_doc, 
				journal_details_created_by_login_id, journal_details_created_ts) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

		error_log($thisPage . "->C", 0);
		$stmt = $mysqli->prepare($strsql);
		if ($mysqli->errno != 0) {
			error_log($thisPage . ": Error at mysqli->prepare(INSERT-creditdata) -> ERROR: " . $mysqli->error, 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}

		// if the total number of elements in creditdata = 1,
		// check if the first record contains anything,
		// if it is empty, then this is an error
		error_log($thisPage . "->F", 0);
		if ((count($creditdata) == 1) && (!isset($creditdata['cr_parent_acct']))) {
			error_log($thisPage . ": Error with [creditdata]-> only one (1) empty credit record found", 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}
		
		$creditRowCtr = $journalheader['journal_credit_rows'];
		error_log($thisPage . "->G:creditRowCtr=" . $creditRowCtr, 0);
		for($i=0; $i<=$creditRowCtr; $i++) {
			
			if ($creditdata['cr_coa_id'][$i] == "") { // skip to the next record to avoid an error

				// do nothing, just skip this record
				error_log($thisPage . "->: skipping record->G: i=" . $i, 0);
			
			} else {
				
				error_log($thisPage . "->H(" . $i . ")", 0);
				
				$cr_rec_type = 'CREDIT';
				$cr_coa_id = $creditdata['cr_coa_id'][$i]; 
				$cr_parent_acct = $creditdata['cr_parent_acct'][$i];
				$cr_sub_acct = $creditdata['cr_sub_acct'][$i];
				$cr_jnl_details = $creditdata['cr_jnl_details'][$i]; 
				$cr_jnl_amt = $creditdata['cr_jnl_amt'][$i];
				$cr_jnl_wtax_type_opt = $creditdata['cr_jnl_wtax_type_opt'][$i];
				$cr_jnl_wtax = $creditdata['cr_jnl_wtax'][$i];
				$cr_jnl_vat_type_opt = $creditdata['cr_jnl_vat_type_opt'][$i];
				$cr_jnl_vat = $creditdata['cr_jnl_vat'][$i];
				$cr_jnl_net = $creditdata['cr_jnl_net'][$i];
				$cr_jnl_ref_doc_opt = $creditdata['cr_jnl_ref_doc_opt'][$i];
				
				error_log($thisPage . ":bind_param()->D", 0);
				$stmt->bind_param("ssisssdsdsddsi", $journal_ref_id, $cr_rec_type, $cr_coa_id, $cr_parent_acct,
						$cr_sub_acct, $cr_jnl_details, $cr_jnl_amt, $cr_jnl_wtax_type_opt, $cr_jnl_wtax,
						$cr_jnl_vat_type_opt, $cr_jnl_vat, $cr_jnl_net, $cr_jnl_ref_doc_opt, $login_id);

				error_log($thisPage . ": execute()->I(" . $i . ")", 0);
				$stmt->execute();
				
				error_log($thisPage . "->J", 0);
				if ($stmt->errno != 0) {
					error_log($thisPage . ": Error at stmt->execute(): INSERT[creditdata(" . $i . ")] -> ERROR No. " . $stmt->errno, 0);
					$stmt->close();
					$mysqli->close();
					return false;
				}
			}
		}
		// $stmt->close();
		
		// ==========================================================================================================
		// insert the debit data row(s) into tbl_journal_entry_details with journal_details_rec_type = 'DEBIT'
		
		// if the total number of elements in debitdata = 1,
		// check if the first record contains anything,
		// if it is empty, then this is an error
		error_log($thisPage . "->M", 0);
		if ((count($debitdata) == 1) && ($debitdata['dr_parent_acct'] == "")) {
			error_log($thisPage . ": Error with [debitdata]-> only one (1) empty debit record found", 0);
			$stmt->close();
			$mysqli->close();
			return false;
		}
		
		$debitRowCtr = $journalheader['journal_debit_rows'];
		error_log($thisPage . "->N:debitRowCtr=" . $debitRowCtr, 0);
		for($i=0; $i<=$debitRowCtr; $i++) {
			
			if ($debitdata['dr_coa_id'][$i] == "") {
				
				// $i++;
				error_log($thisPage . "->O: increment i=" . $i, 0);
				
			} else {
				
				error_log($thisPage . "->P: increment i=" . $i, 0);
				
				$dr_rec_type = 'DEBIT';
				$dr_coa_id = $debitdata['dr_coa_id'][$i];
				$dr_parent_acct = $debitdata['dr_parent_acct'][$i];
				$dr_sub_acct = $debitdata['dr_sub_acct'][$i];
				$dr_jnl_details = $debitdata['dr_jnl_details'][$i];
				$dr_jnl_amt = $debitdata['dr_jnl_amt'][$i];
				$dr_jnl_wtax_type_opt = $debitdata['dr_jnl_wtax_type_opt'][$i];
				$dr_jnl_wtax = $debitdata['dr_jnl_wtax'][$i];
				$dr_jnl_vat_type_opt = $debitdata['dr_jnl_vat_type_opt'][$i];
				$dr_jnl_vat = $debitdata['dr_jnl_vat'][$i];
				$dr_jnl_net = $debitdata['dr_jnl_net'][$i];
				$dr_jnl_ref_doc_opt = $debitdata['dr_jnl_ref_doc_opt'][$i];

				error_log($thisPage . ":bind_param()->D", 0);
				$stmt->bind_param("ssisssdsdsddsi", $journal_ref_id, $dr_rec_type, $dr_coa_id, $dr_parent_acct,
						$dr_sub_acct, $dr_jnl_details, $dr_jnl_amt, $dr_jnl_wtax_type_opt, $dr_jnl_wtax,
						$dr_jnl_vat_type_opt, $dr_jnl_vat, $dr_jnl_net, $dr_jnl_ref_doc_opt, $login_id);
				
				error_log($thisPage . ": execute()->Q(" . $i . ")", 0);
				$stmt->execute();

				error_log($thisPage . "->R", 0);
				if ($stmt->errno != 0) {
					error_log($thisPage . ": Error at stmt->execute(): INSERT[debitdata(" . $i . ")] -> ERROR No. " . $stmt->errno, 0);
					$stmt->close();
					$mysqli->close();
					return false;
				}
			}
		}

		error_log($thisPage . "->S", 0);
		$stmt->close();
		$mysqli->close();
		
		return true;
		
	}
}

?>

