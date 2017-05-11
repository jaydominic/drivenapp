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
		
		if (!isset($_REQUEST['operation'])) {
			
			echo "ERROR: " . $thisPage . " -> No data operation specified";
			exit();
			
		} else { // INSERT
			/*
			 $userdata = array(
					'operation' => $_REQUEST['operation'], 'json' => $_REQUEST['json'], 'calling_page' => $_REQUEST['calling_page'],
					
					'journal_reference_id' => $_REQUEST['journal_reference_id'], 'journal_entry_date' => $_REQUEST['journal_entry_date'], 
					'journal_period' => $_REQUEST['journal_period'], 'journal_posting_date' => $_REQUEST['journal_posting_date'],
					'journal_txn_type' => $_REQUEST['journal_txn_type'], 'journal_gen_desc' => $_REQUEST['journal_gen_desc'], 
					'journal_txn_class' => $_REQUEST['journal_txn_class'],
					
					'parent_acct' => $_REQUEST['parent_acct'][0], 'sub_acct' => $_REQUEST['sub_acct'][0], 
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
			echo "count=" . count($_REQUEST['parent_acct']);
			// var_dump($userdata);
			exit();
			
			// $result = update_user_acct($dbconn, $_REQUEST['operation'], $_REQUEST['user_id_selected'], $userdata);
		}
	default:
		
		break;
}

if ($result == false) {
	// something went wrong with the update
	error_log("UpdateControl.php:  Something went wrong at " . $called_module, 0);

} else {

	// OK!
	error_log("UpdateControl.php: " . $called_module . " return " . $result . ": Returning to " . $_REQUEST['calling_page'], 0);

}

?>
<script type='text/javascript'>gotoURL('../views/<?php echo $_REQUEST['calling_page'] ?>')</script>
</form>
</body>
</html>