<!DOCTYPE html>
<html>
<?php

$thisPage = basename(__FILE__);

error_log($thisPage . ":  START", 0);

if (!isset($_REQUEST['calling_page'])) {
	echo "ERROR: " . $thisPage . " -> calling page parameter not found.";
	exit();
}

// define all the necessary include php files here
require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../model/update_user_roles.php';
require_once '../model/add_user_roles.php';
require_once '../model/update_user_acct.php';
require_once '../model/update_gen_journal.php';

$jsonstr = json_decode($_REQUEST['json']);
$login_id = $jsonstr->login_id;

// include the external javascript file
?>
<body>
<form name="frmMain" id="frmMain">
<script type="text/javascript" src="../views/js/<?php echo $jsfile ?>"></script>
<input type="hidden" name="json" id="json" value='<?php echo $_REQUEST['json'] ?>'>
<?php 

error_log($thisPage . ":  calling_page==" . $_REQUEST['calling_page'], 0);
error_log($thisPage . ":  operation==" . $_REQUEST['operation'], 0);
// error_log("updatecontrol.php:  operation==" . $_REQUEST['cb_menu_code'], 0);

switch ($_REQUEST['calling_page']) {
	
	case "manage_user_roles.php":
		
		echo "<input type='hidden' name='role_id_selected' id='role_id_selected' value='" . $_REQUEST['role_id_selected'] . "'>";

		if (!isset($_REQUEST['operation'])) {
		
			echo "ERROR: " . $thisPage . " -> No data operation specified";
			exit();
		
		} elseif ($_REQUEST['operation'] == "INSERT") {
			
			$cnt = (isset($_REQUEST['cb_menu_code']) ? count($_REQUEST['cb_menu_code']) : 0);
			if ($cnt == 0) {
				$result = add_user_roles($dbconn, $_REQUEST['role_name_new'], $_REQUEST['role_desc_new'], $login_id);
			} else {
				$result = add_user_roles($dbconn, $_REQUEST['role_name_new'], $_REQUEST['role_desc_new'], $login_id, $_REQUEST['cb_menu_code']);
			}
				
		} else {  // ENABLE, DISABLE, UPDATE operations

			// set a default value for this request object to avoid errors
			$cnt = (isset($_REQUEST['cb_menu_code']) ? count($_REQUEST['cb_menu_code']) : 0);
			if ($cnt == 0) {
				$result = update_user_roles($dbconn, $_REQUEST['role_id_selected'], $_REQUEST['operation'], $login_id, $_REQUEST['role_desc']);
			} else {
				$result = update_user_roles($dbconn, $_REQUEST['role_id_selected'], $_REQUEST['operation'], $login_id, $_REQUEST['role_desc'], $_REQUEST['cb_menu_code']);
			}
		
		}
		$called_module = "update_user_roles.php";
		break;
	
	case "manage_users.php":

		echo "<input type='hidden' name='user_id_selected' id='user_id_selected' value='" . $_REQUEST['user_id_selected'] . "'>";
		
		if (!isset($_REQUEST['operation'])) {
		
			echo "ERROR: " . $thisPage . " -> No data operation specified";
			exit();
		
		} else {
				
			// consolidate the data to update into an array object 
			// and pass as parameter to update_user_acct() function
			// NOTE: userdata variable will only contain value pairs and 
			//       not be indexed since it was not declared as userdata[]
			$userdata = array('user_id' => $_REQUEST['user_id_selected'], 'user_username' => $_REQUEST['user_username_selected'],
						'user_password' => $_REQUEST['new_password'], 'user_firstname' => $_REQUEST['user_firstname_selected'], 
						'user_lastname' => $_REQUEST['user_lastname_selected'],	'user_middlename' => $_REQUEST['user_middlename_selected'], 
						'user_fullname' => $_REQUEST['user_fullname_selected'],	'user_email' => $_REQUEST['user_email_selected'], 
						'user_company' => $_REQUEST['user_company_selected'], 'user_branch' => $_REQUEST['user_branch_selected'],
						'user_role_id' => $_REQUEST['role_id_selected'], 'user_is_active' => $_REQUEST['user_is_active_selected'], 
						'user_status' => $_REQUEST['user_status_selected'],	'user_role_menu_codes' => $_REQUEST['role_menu_codes_selected']);
							
			$result = update_user_acct($dbconn, $_REQUEST['operation'], $_REQUEST['user_id_selected'], $userdata);
		
		}
		$called_module = "update_user_acct.php";
		break;
	
	case "gen_journal_entry.php":
		
		$called_module = "update_gen_journal.php";
		
		if (!isset($_REQUEST['operation'])) {
			
			echo "ERROR: " . $thisPage . " -> No data operation specified";
			exit();
			
		} else { // INSERT
			
			// put the header journal data into the 1st array
			if (isset($_REQUEST['journal_reference_id'])) {
				
				// convert the date format from mm-dd-yyyy into yyyy-mm-dd
				$journal_entry_date = substr($_REQUEST['journal_entry_date'], 6, 4) . "-" . substr($_REQUEST['journal_entry_date'], 0, 5);
				$journal_period = substr($_REQUEST['journal_period'], 6, 4) . "-" . substr($_REQUEST['journal_period'], 0, 5);
				$journal_posting_date = substr($_REQUEST['journal_posting_date'], 6, 4) . "-" . substr($_REQUEST['journal_posting_date'], 0, 5);
				
				$journal_entry_date = date_format(date_create($journal_entry_date), "Y-m-d");
				$journal_period = date_format(date_create($journal_period), "Y-m-d");
				$journal_posting_date = date_format(date_create($journal_posting_date), "Y-m-d");

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
				
			} else {
				error_log("header=null", 0);
				$result = false;
			}

			// put the multi-row credit data into the 2nd array
			if (isset($_REQUEST['cr_parent_acct'])) {

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
			} else {
				error_log("credit=null", 0);
				$result = false;
			}
			
			// put the multi-row debit data into the 3rd array
			if (isset($_REQUEST['dr_parent_acct'])) {

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
			} else {
				error_log("debit=null", 0);
				$result = false;
			}

			$result = update_gen_journal($dbconn, $_REQUEST['operation'], $login_id, $journalheader, $creditdata, $debitdata);
			break;
		}
	default:
		
		break;
}

if ($result == false) {
	// something went wrong with the update
	error_log($thisPage . ":  Something went wrong at " . $called_module, 0);
	$error_msg = "Journal Entry save operation was unsuccessful.";
	$error_msg = urlencode($error_msg);
	
} else {

	// OK!
	error_log($thisPage . ": " . $called_module . " returned " . $result . ": Returning to " . $_REQUEST['calling_page'], 0);
	$error_msg = "Journal Entry successfully saved.";
	$error_msg = urlencode($error_msg);
}

?>

<script type='text/javascript'>gotoURL("../views/<?php echo $_REQUEST['calling_page'] ?>?error_msg=<?php echo $error_msg ?>")</script>
</form>
</body>
</html>


