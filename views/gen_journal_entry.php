<!DOCTYPE html>
<html>
<?php

// define page-specific variables here
$page_menu_code = "A0008";

// define default fallback page in case of error or if user clicks on the main menu button
$homeURL = "../views/home.php";

// define the current php filename
$thisPage = "gen_journal_entry.php";

// define the target php filename when doing a POST
$targetPage = "../controller/updatecontrol.php";

error_log($thisPage . ": START", 0);

// load the necessary functions/files to be called here
require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../views/instring.php';
require_once '../model/get_fullname.php';
require_once '../model/get_role_desc.php';


// URL decode the JSON parameter, decode the JSON data and store into a variable
$jsonstr = json_decode($_REQUEST['json']);

// check if the user is authorized to use this page
if ((instring($jsonstr->str_menu_codes, $page_menu_code) == false) && ($jsonstr->menu_code == $page_menu_code)) {

	error_log($thisPage . ": Menu code " . $page_menu_code . " not found in str_menu_codes.", 0);

	echo "<script type='text/javascript' src='../views/js/" . $jsfile . "'></script>";
	echo "<form name='frmMain' id='frmMain'>";
	echo "<input type='hidden' name='json' id='json' value='" . $jsonstr . "'>";
	echo "</form>";
	echo "<script type='text/javascript'>gotoURL('" . $homeURL . "')</script>";
	exit();

} else {

	// get all the necessary variables ready
	$session_id = $jsonstr->session_id;
	$login_id = $jsonstr->login_id;
	$login_username = $jsonstr->login_username;
	$str_menu_codes = $jsonstr->str_menu_codes;
	
	$login_fullname = get_fullname($login_id, $dbconn);
	$login_role_desc = get_role_desc($dbconn, $login_id);
	
	// setup the browser window and include the necessary CSS, Javascript and header files
?>
	<head>
		<title><?php echo $browsertitle ?></title>
		<link rel='stylesheet' type='text/css' href='css/<?php echo $cssfile ?>'>
	</head>
	<body onload="document.getElementById('journal_entry_date').focus()" onunload="closeAcctWindows()">
		<div><img src='images/<?php echo $logofile2 ?>' alt='Driven Logo' class='header-img-small' /></div>
		<hr>
		<form name='frmMain' id='frmMain'>
	
			<!-- START: store the variables in hidden textboxes for a POST method  -->
	
			<!-- Variables used to store information about the current user -->
			<input type='hidden' name='login_id' id='login_id' value='<?php echo $login_id ?>'>
			
			<!-- Miscellaneous page specific variables -->
			<input type='hidden' name='operation' id='operation'>
			<input type='hidden' name='calling_page' id='calling_page' value='<?php echo $thisPage ?>'>
			<input type='hidden' name='json' id='json' value='<?php echo json_encode($jsonstr) ?>'>
	
			<!-- END: store the variables in hidden textboxes for a POST method  -->
	
			<table class="hompagebody">
				<tr>
					<td width="50%" align="left">
						Current User:&nbsp;&nbsp;<b><?php echo $login_fullname ?></b><br />
						You are logged in as: &nbsp;<i><b><?php echo $login_username ?>&nbsp;&nbsp;(<?php echo $login_role_desc ?>)</b></i>
					</td>
					<td width="50%" align="right">
						<input type="button" value="LOGOUT" class="logoutbutton" onclick="logoff()">
					</td>
				</tr>
			</table>
			
			CR CTR<input type='text' id='creditRowCtr' value='0'><br>
			DR CTR<input type='text' id='debitRowCtr' value='0'>

			<!-- START: Main section of the page -->

			<table class='main-table'>

				<tr><td class='main-title' colspan="4"><?php echo $page_menu_code ?> - G E N E R A L&nbsp;&nbsp;&nbsp;J O U R N A L&nbsp;&nbsp;&nbsp;D A T A&nbsp;&nbsp;&nbsp;E N T R Y</td></tr>

				<tr class='sub-table'>
					<td class='main-body-text-right3' width='12%'>Reference ID No.:</td>
					<td class='main-body-text-left2' width='38%'>
						<input type='text' name='journal_reference_id' id='journal_reference_id' class='input-medium'>&nbsp;&nbsp;<span class='alert-text2'>(required)</span>
					</td>

					<td class='main-body-text-right3' width='12%'>Journal Entry Date:</td>
					<td class='main-body-text-left2' width='38%'>
						<input type='text' name='journal_entry_date' id='journal_entry_date' class='input-short'>
						<img src="images/<?php echo $calendar_img ?>" onclick="NewCssCal('journal_entry_date')" style="cursor: pointer;" />&nbsp;&nbsp;
						<span class='alert-text2'>(required)</span>
					</td>
				</tr>

				<tr class='sub-table'>
					<td class='main-body-text-right3'>Period:</td>
					<td class='main-body-text-left2'>
						<input type='text' name='journal_period' id='journal_period' class='input-short'>
						<img src="images/<?php echo $calendar_img ?>" onclick="NewCssCal('journal_period')" style="cursor: pointer;" />&nbsp;&nbsp;
						<span class='alert-text2'>(required)</span>
					</td>

					<td class='main-body-text-right3'>Posting Date:</td>
					<td class='main-body-text-left2'>
						<input type='text' name='journal_posting_date' id='journal_posting_date' class='input-short'>
						<img src="images/<?php echo $calendar_img ?>" onclick="NewCssCal('journal_posting_date')" style="cursor: pointer;" />&nbsp;&nbsp;
						<span class='alert-text2'>(required)</span>
					</td>
				</tr>

				<tr class='sub-table'>
					<td class='main-body-text-right3'>Transaction Type:</td>
					<td class='main-body-text-left2'>
						<select name='journal_txn_type' id='journal_txn_type' class='input-medium'>
							<option value='NONE' selected>&lt;select transaction type&gt;</option>
							<option value='ADJUSTMENTS'>Adjustments</option>
							<option value='COMMISSION'>Commission</option>
							<option value='LIQUIDATION'>Liquidation</option>
							<option value='REIMBURSEMENT'>Reimbursement</option>
						</select>
						<span class='alert-text2'>(required)</span>
					</td>

					<td class='main-body-text-right3' rowspan='2'>Description:</td>
					<td class='main-body-text-left2' rowspan='2'>
						<textarea name='journal_gen_desc' id='journal_gen_desc' rows="3" cols="40"></textarea>
						<span class='alert-text2'>(required)</span>
					</td>
				</tr>

				<tr class='sub-table'>
					<td class='main-body-text-right3'>Transaction Class:</td>
					<td class='main-body-text-left2'>
						<select name='journal_txn_class' id='journal_txn_class' class='input-medium'>
							<option value='NONE'>&lt;select transaction type&gt;</option>
							<option value='CA'>Cash Advance</option>
							<option value='CV'>Check Voucher</option>
						</select>
						<span class='alert-text2'>(required)</span>
					</td>
				</tr>

				<tr>
					<td width="100%" class='sub-table' colspan="4">

						<!-- BEGIN: CREDIT SECTION -->

						<table class='main-table'>
							<tr><td class='main-sub-title-left'>A.&nbsp;&nbsp;CREDIT ENTRIES</td></tr>
							
							<tr><td>
							
								<table class="sub-table" width="100%" border="1">
									<thead class="highlight-row-blue">
										<tr>
											<th class="main-body-text-center">Parent Account</th>
											<th class="main-body-text-center">Sub-Account</th>
											<th class="main-body-text-center">Details</th>
											<th class="main-body-text-center">Amount</th>
											<th class="main-body-text-center">EWT%</th>
											<th class="main-body-text-center">EWT Amount</th>
											<th class="main-body-text-center">VAT Type</th>
											<th class="main-body-text-center">VAT Amount</th>
											<th class="main-body-text-center">NET Amount</th>
											<th class="main-body-text-center">Ref. Doc.</th>
											<th class="main-body-text-center">Action</th>
										</tr>
									</thead>
									
									<tbody id="creditEntrySection">

										<!-- BEGIN: This is where the user enters journal data -->

										<tr class="highlight-row-yellow2">
											<td>
												<div id="cr_parent_account_tooltip" title="PARENT ACCOUNT">
													<input type='text' name='cr_parent_account' id='cr_parent_account' class='input-short' disabled><br>
													<input type='button' id='crAddParentAcctBtn' value='Select Parent Account' class='cmdbutton3' onclick="pickAcct('<?php echo $login_username ?>','<?php echo $session_id ?>', 'CREDIT')">
												</div>
											</td>
											<td>
												<div id="cr_sub_account_tooltip" title="SUB-ACCOUNT">
													<input type='text' name='cr_sub_account' id='cr_sub_account' class='input-short' disabled><br>
													<input type='button' id='crAddSubAcctBtn' value='Select Sub-Account' class='cmdbutton3' onclick="pickSubAcct('<?php echo $login_username ?>', '<?php echo $session_id ?>', document.getElementById('cr_parent_account').value, 'CREDIT')">
												</div>
											</td>
											<td>
												<textarea rows="3" cols="20" name='cr_journal_details' id='cr_journal_details'></textarea>
											</td>
											<td><input type='text' name='cr_journal_amt' id='cr_journal_amt' value='0.00' class='input-amount' onclick="selectAll('journal_amt')"></td>
											<td>
												<select name='cr_journal_wtax_type' id='cr_journal_wtax_type' onchange="computeWTAX('CREDIT')">
													<option value='' selected></option>
													<option value='0'>0%</option>
													<option value='1'>1%</option>
													<option value='2'>2%</option>
													<option value='5'>5%</option>
													<option value='10'>10%</option>
													<option value='15'>15%</option>
												</select>
											</td>
											<td><input type='text' name='cr_journal_wtax' id='cr_journal_wtax' value='0.00' class='input-amount' onclick="selectAll('journal_wtax')"></td>
											<td>
												<select name='cr_journal_vat_type' id='cr_journal_vat_type' onchange="computeVAT('CREDIT')">
													<option value='EXEMPT' selected>VAT Exempt</option>
													<option value='VATREG'>VAT Registered</option>
													<option value='NONVAT'>Zero-Rated VAT</option>
												</select>
											</td>
											<td><input type='text' name='cr_journal_vat' id='cr_journal_vat' value='0.00' class='input-amount' onclick="selectAll('journal_vat')"></td>
											<td><input type='text' name='cr_journal_net' id='cr_journal_net' value='0.00' class='input-amount' onclick="selectAll('journal_net')"></td>
											<td>
												<select name='cr_journal_ref_doc' id='journal_ref_doc'>
													<option value='NONE' selected>N/A</option>
													<option value='CA'>Cash Advance</option>
													<option value='CV'>Check Voucher</option>
												</select>
											</td>
											<td><input type='button' id='addCreditBtn' value='Add' class='cmdbutton-small' onclick="addCreditRow()"></td>
										</tr>

										<!-- END: This is where the user enters journal data -->

										<!-- BEGIN: This is the template for showing the user-entered journal data -->
	
										<tr id="creditEntryRow" class="highlight-row-yellow3" style="display: none;">
											<td>
												<div id="cr_parent_acct_tooltip" title="PARENT ACCOUNT">
													<input type='text' name='cr_parent_acct[]' id='cr_parent_acct' class='input-short' disabled>
												</div>
											</td>
											<td>
												<div id="cr_sub_acct_tooltip" title="SUB-ACCOUNT">
													<input type='text' name='cr_sub_acct[]' id='cr_sub_acct' class='input-short' disabled>
												</div>
											</td>
											<td>
												<textarea rows="3" cols="20" name='cr_jnl_details[]' id='cr_jnl_details' disabled></textarea>
											</td>
											<td><input type='text' name='cr_jnl_amt[]' id='cr_jnl_amt' class='input-amount' disabled></td>
											<td>
												<input type='text' name='cr_jnl_wtax_type[]' id='cr_jnl_wtax_type' class='input-very-short2' disabled>
												<br>
												<input type='text' name='cr_jnl_wtax_type_opt[]' id='cr_jnl_wtax_type_opt' class='input-very-short2'>
											</td>
											<td><input type='text' name='cr_jnl_wtax[]' id='cr_jnl_wtax' class='input-amount' disabled></td>
											<td>
												<input type='text' name='cr_jnl_vat_type[]' id='cr_jnl_vat_type' class='input-very-short3' disabled>
												<br>
												<input type='text' name='cr_jnl_vat_type_opt[]' id='cr_jnl_vat_type_opt' class='input-very-short3'>
											</td>
											<td><input type='text' name='cr_jnl_vat[]' id='cr_jnl_vat' class='input-amount' disabled></td>
											<td><input type='text' name='cr_jnl_net[]' id='cr_jnl_net' class='input-amount' disabled></td>
											<td>
												<input type='text' name='cr_jnl_ref_doc[]' id='cr_jnl_ref_doc' class='input-very-short3' disabled>
												<br>
												<input type='text' name='cr_jnl_ref_doc_opt[]' id='cr_jnl_ref_doc_opt' class='input-very-short3'>
											</td>
											<td><input type='button' id='crDelCreditBtn' value='Remove' class='cmdbutton-small' onclick="delCreditRow(event)"></td>
										</tr>

										<!-- END: This is where the user-entered data is displayed until it is saved to DB -->

									</tbody>
									
									<tfoot class="main-sub-title2">
										<tr>
											<td colspan="3" class="main-body-text-right2">Credit Entry Totals:</td>
											<td style="align: center;"><input id="cr_total_amt" class="input-amount" value="0.00" readOnly></td>
											<td>&nbsp;</td>
											<td style="align: center;"><input id="cr_total_wtax" class="input-amount" value="0.00" readOnly></td>
											<td>&nbsp;</td>
											<td style="align: center;"><input id="cr_total_vat" class="input-amount" value="0.00" readOnly></td>
											<td style="align: center;"><input id="cr_total_net" class="input-amount" value="0.00" readOnly></td>
											<td>&nbsp;</td>
											<td></td>
										</tr>
									</tfoot>
								</table>
							
							</td></tr>
						</table>

						<!-- END: CREDIT SECTION -->

						<!-- BEGIN: DEBIT SECTION -->

						<table class='main-table'>
							<tr><td class='main-sub-title-left'>B.&nbsp;&nbsp;DEBIT ENTRIES</td></tr>

							<tr><td>
							
								<table class="sub-table" width="100%" border="1">
									<thead class="highlight-row-blue">
										<tr>
											<th class="main-body-text-center">Parent Account</th>
											<th class="main-body-text-center">Sub-Account</th>
											<th class="main-body-text-center">Details</th>
											<th class="main-body-text-center">Amount</th>
											<th class="main-body-text-center">EWT%</th>
											<th class="main-body-text-center">EWT Amount</th>
											<th class="main-body-text-center">VAT Type</th>
											<th class="main-body-text-center">VAT Amount</th>
											<th class="main-body-text-center">NET Amount</th>
											<th class="main-body-text-center">Ref. Doc.</th>
											<th class="main-body-text-center">Action</th>
										</tr>
									</thead>
									
									<tbody id="debitEntrySection">

										<!-- BEGIN: This is where the user enters journal data -->

										<tr class="highlight-row-yellow2">
											<td>
												<div id="dr_parent_account_tooltip" title="PARENT ACCOUNT">
													<input type='text' name='dr_parent_account' id='dr_parent_account' class='input-short' disabled><br>
													<input type='button' id='drAddParentAcctBtn' value='Select Parent Account' class='cmdbutton3' onclick="pickAcct('<?php echo $login_username ?>','<?php echo $session_id ?>', 'DEBIT')">
												</div>
											</td>
											<td>
												<div id="dr_sub_account_tooltip" title="SUB-ACCOUNT">
													<input type='text' name='dr_sub_account' id='dr_sub_account' class='input-short' disabled><br>
													<input type='button' id='drAddSubAcctBtn' value='Select Sub-Account' class='cmdbutton3' onclick="pickSubAcct('<?php echo $login_username ?>', '<?php echo $session_id ?>', document.getElementById('dr_parent_account').value, 'DEBIT')">
												</div>
											</td>
											<td>
												<textarea rows="3" cols="20" name='dr_journal_details' id='dr_journal_details'></textarea>
											</td>
											<td><input type='text' name='dr_journal_amt' id='dr_journal_amt' value='0.00' class='input-amount' onclick="selectAll('journal_amt')"></td>
											<td>
												<select name='dr_journal_wtax_type' id='dr_journal_wtax_type' onchange="computeWTAX('DEBIT')">
													<option value='' selected></option>
													<option value='0'>0%</option>
													<option value='1'>1%</option>
													<option value='2'>2%</option>
													<option value='5'>5%</option>
													<option value='10'>10%</option>
													<option value='15'>15%</option>
												</select>
											</td>
											<td><input type='text' name='dr_journal_wtax' id='dr_journal_wtax' value='0.00' class='input-amount' onclick="selectAll('journal_wtax')"></td>
											<td>
												<select name='dr_journal_vat_type' id='dr_journal_vat_type' onchange="computeVAT('DEBIT')">
													<option value='EXEMPT' selected>VAT Exempt</option>
													<option value='VATREG'>VAT Registered</option>
													<option value='NONVAT'>Zero-Rated VAT</option>
												</select>
											</td>
											<td><input type='text' name='dr_journal_vat' id='dr_journal_vat' value='0.00' class='input-amount' onclick="selectAll('journal_vat')"></td>
											<td><input type='text' name='dr_journal_net' id='dr_journal_net' value='0.00' class='input-amount' onclick="selectAll('journal_net')"></td>
											<td>
												<select name='dr_journal_ref_doc' id='dr_journal_ref_doc'>
													<option value='NONE' selected>N/A</option>
													<option value='CA'>Cash Advance</option>
													<option value='CV'>Check Voucher</option>
												</select>
											</td>
											<td><input type='button' id='drAddDebitBtn' value='Add' class='cmdbutton-small' onclick="addDebitRow()"></td>
										</tr>

										<!-- END: This is where the user enters journal data -->

										<!-- BEGIN: This is the template for showing the user-entered journal data -->
	
										<tr id="debitEntryRow" class="highlight-row-yellow3" style="display: none;">
											<td>
												<div id="dr_parent_acct_tooltip" title="PARENT ACCOUNT">
													<input type='text' name='dr_parent_acct[]' id='dr_parent_acct' class='input-short' disabled>
												</div>
											</td>
											<td>
												<div id="sub_acct_tooltip2" title="SUB-ACCOUNT">
													<input type='text' name='dr_sub_acct[]' id='dr_sub_acct' class='input-short' disabled>
												</div>
											</td>
											<td>
												<textarea rows="3" cols="20" name='dr_jnl_details[]' id='dr_jnl_details' disabled></textarea>
											</td>
											<td><input type='text' name='dr_jnl_amt[]' id='dr_jnl_amt' class='input-amount' disabled></td>
											<td>
												<input type='text' name='dr_jnl_wtax_type[]' id='dr_jnl_wtax_type' class='input-very-short2' disabled>
												<br>
												<input type='text' name='dr_jnl_wtax_type_opt[]' id='dr_jnl_wtax_type_opt' class='input-very-short2'>
											</td>
											<td><input type='text' name='dr_jnl_wtax[]' id='dr_jnl_wtax' class='input-amount' disabled></td>
											<td>
												<input type='text' name='dr_jnl_vat_type[]' id='dr_jnl_vat_type' class='input-very-short3' disabled>
												<br>
												<input type='text' name='dr_jnl_vat_type_opt[]' id='dr_jnl_vat_type_opt' class='input-very-short3'>
											</td>
											<td><input type='text' name='dr_jnl_vat[]' id='dr_jnl_vat' class='input-amount' disabled></td>
											<td><input type='text' name='dr_jnl_net[]' id='dr_jnl_net' class='input-amount' disabled></td>
											<td>
												<input type='text' name='dr_jnl_ref_doc[]' id='dr_jnl_ref_doc' class='input-very-short3' disabled>
												<br>
												<input type='text' name='dr_jnl_ref_doc_opt[]' id='dr_jnl_ref_doc_opt' class='input-very-short3'>
											</td>
											<td><input type='button' id='drDelDebitBtn' value='Remove' class='cmdbutton-small' onclick="delDebitRow(event)"></td>
										</tr>

										<!-- END: This is where the user-entered data is displayed until it is saved to DB -->

									</tbody>
									
									<tfoot class="main-sub-title2">
										<tr>
											<td colspan="3" class="main-body-text-right2">Debit Entry Totals:</td>
											<td style="align: center;"><input id="dr_total_amt" class="input-amount" value="0.00" readOnly></td>
											<td>&nbsp;</td>
											<td style="align: center;"><input id="dr_total_wtax" class="input-amount" value="0.00" readOnly></td>
											<td>&nbsp;</td>
											<td style="align: center;"><input id="dr_total_vat" class="input-amount" value="0.00" readOnly></td>
											<td style="align: center;"><input id="dr_total_net" class="input-amount" value="0.00" readOnly></td>
											<td>&nbsp;</td>
											<td></td>
										</tr>
									</tfoot>
								</table>
							
							</td></tr>

						</table>

						<!-- END: DEBIT SECTION -->


					</td>
				</tr>
			</table>

			<!-- START of BOTTOM MENU BUTTONS section -->
			
			<table class='main-table'>
				<tr>
					<td class='main-title' colspan='5'>
						<input type="button" value="Submit Changes" id="cmdSubmit" class="cmdbutton2" onclick="updateData('<?php echo $thisPage ?>','<?php echo $targetPage ?>','INSERT')">
						&nbsp;&nbsp;
						<input type="button" value="Back to Menu" id="cmdMenu"  class="cmdbutton2" onclick="gotoURL('<?php echo $homeURL ?>')">
					</td>
				</tr>
			</table>

			<!-- END: Main section of the page -->

<?php

}

?>
		</form>
		<script type='text/javascript' src='js/<?php echo $jsfile ?>'></script>
		<script type="text/javascript" src='js/<?php echo $jsfile2 ?>'></script>
	</body>
</html>

