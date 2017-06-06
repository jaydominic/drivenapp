<?php 

require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../model/get_journal_details.php';

$thisPage = basename(__FILE__);

error_log($thisPage . ": START", 0);

if (!isset($_REQUEST['jnl_ref_id'])) {
	error_log($thisPage . ": No jnl_ref_id found.", 0);
?>

<table class='sub-table' width='100%'>
	<thead class="highlight-row-blue">
		<tr>
			<th class="main-body-text-center">Ref. ID No.</th>
			<th class="main-body-text-center">Parent Account</th>
			<th class="main-body-text-center">Sub-Account</th>
			<th class="main-body-text-center">Details</th>
			<th class="main-body-text-center">Amount</th>
			<th class="main-body-text-center">EWT%</th>
			<th class="main-body-text-center">EWT Amount</th>
			<th class="main-body-text-center">VAT Type</th>
			<th class="main-body-text-center">VAT Amount</th>
			<th class="main-body-text-center">NET Amount</th>
			<th class="main-body-text-center">Ref Doc.</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td cols='11'><i>No records found</i></td>
		</tr>
	</tbody>
</table>

<?php

} else {  // jnl_ref_id was specified

	error_log($thisPage . ": jnl_ref_id=" . $_REQUEST['jnl_ref_id'], 0);
	$jnl_ref_id = $_REQUEST['jnl_ref_id'];

	if (!isset($_REQUEST['jnl_rec_type'])) {
		$jnl_rec_type = NULL;
	} else if ($_REQUEST['jnl_rec_type'] == "DEBIT") {
		$jnl_rec_type = "DEBIT";
	} else {
		$jnl_rec_type = "CREDIT";
	}
	
	/*
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
	 */
	$list_journal = get_journal_details($dbconn, $jnl_ref_id, $jnl_rec_type);
	$list_rows = count($list_journal);
}
?>
<table class='sub-table' width='100%'>
	<thead class="highlight-row-blue">
		<tr>
			<th class="main-body-text-center" width="8%">Ref. ID No.</th>
			<th class="main-body-text-center" width="15%">Parent Account</th>
			<th class="main-body-text-center" width="15%">Sub-Account</th>
			<th class="main-body-text-center" width="15%">Details</th>
			<th class="main-body-text-center" width="7%">Amount</th>
			<th class="main-body-text-center" width="4%">EWT%</th>
			<th class="main-body-text-center" width="7%">EWT Amount</th>
			<th class="main-body-text-center" width="7%">VAT Type</th>
			<th class="main-body-text-center" width="7%">VAT Amount</th>
			<th class="main-body-text-center" width="7%">NET Amount</th>
			<th class="main-body-text-center" width="8%">Ref Doc.</th>
		</tr>
	</thead>
	<tbody>
<?php
		if (isset($list_journal)) {
			for ($i=0; $i<$list_rows; $i++) {
?>
		<tr>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_details_ref_id'] ?></td>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_details_parent_acct'] ?></td>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_details_sub_acct'] ?></td>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_details_desc'] ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_details_amount'], 2) ?></td>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_details_wtax'] ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_details_wtax_amount'], 2) ?></td>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_details_vat'] ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_details_vat_amount'], 2) ?></td>
			<td class="main-body-text-right"><?php echo number_format($list_journal[$i]['journal_details_net_amount'], 2) ?></td>
			<td class="main-body-text-left"><?php echo $list_journal[$i]['journal_details_ref_doc'] ?></td>
		</tr>
<?php 
			}
		} else {
?>
		<tr>
			<td cols='11'><i>No records found</i></td>
		</tr>
<?php 
		}
?>
	</tbody>						
</table>


