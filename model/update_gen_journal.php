<?php

/* 
 		$userdata variable contains an array with the following fields:
 		
		$userdata = array(
				'operation' => $_REQUEST['operation'], 'json' => $_REQUEST['json'], 'calling_page' => $_REQUEST['calling_page'],
				
				'journal_reference_id' => $_REQUEST['journal_reference_id'], 'journal_entry_date' => $_REQUEST['journal_entry_date'], 
				'journal_period' => $_REQUEST['journal_period'], 'journal_posting_date' => $_REQUEST['journal_posting_date'],
				'journal_txn_type' => $_REQUEST['journal_txn_type'], 'journal_gen_desc' => $_REQUEST['journal_gen_desc'], 
				'journal_txn_class' => $_REQUEST['journal_txn_class'],
				
				'parent_acct' => $_REQUEST['parent_acct'], 'sub_acct' => $_REQUEST['sub_acct'], 
				'jnl_details' => $_REQUEST['jnl_details'], 'jnl_amt' => $_REQUEST['jnl_amt'],
				'jnl_wtax_type_opt' => $_REQUEST['jnl_wtax_type_opt'], 'jnl_wtax' => $_REQUEST['jnl_wtax'],
				'jnl_vat_type_opt' => $_REQUEST['jnl_vat_type_opt'], 'jnl_vat' => $_REQUEST['jnl_vat'],
				'jnl_net' => $_REQUEST['jnl_net'], 'jnl_ref_doc_opt' => $_REQUEST['jnl_ref_doc_opt'], 
				
				'parent_acct2' => $_REQUEST['parent_acct2'], 'sub_acct2' => $_REQUEST['sub_acct2'],
				'jnl_details2' => $_REQUEST['jnl_details2'], 'jnl_amt2' => $_REQUEST['jnl_amt2'], 
				'jnl_wtax_type_opt2' => $_REQUEST['jnl_wtax_type_opt2'], 'jnl_wtax2' => $_REQUEST['jnl_wtax2'],
				'jnl_vat_type_opt2' => $_REQUEST['jnl_vat_type_opt2'], 'jnl_vat2' => $_REQUEST['jnl_vat2'], 
				'jnl_net2' => $_REQUEST['jnl_net2'], 'jnl_ref_doc_opt2' => $_REQUEST['jnl_ref_doc_opt2']
		);
 */

function update_gen_journal($dbconn, $operation, $login_id, $userdata) {
	
	$thisPage = __FILE__;
	
	error_log($thisPage . ":  START", 0);
	
	// all parameters are required, check first if any are missing / null
	// error_log("update_user_acct.php:  checking parameter [dbconn]", 0);
	if (!isset($dbconn)) {
		error_log($thisPage . ":  ERROR -> Parameter [dbconn] missing", 0);
		return false;
	}

	//====================================================================================================
	// check the type of operation being requested
	if ($operation == "INSERT") {

	}
		
}

?>
