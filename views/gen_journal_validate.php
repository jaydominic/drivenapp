<!DOCTYPE html>
<html>
<?php

// define page-specific variables here
$page_menu_code = "A0009";

// define default fallback page in case of error or if user clicks on the main menu button
$homeURL = "../views/home.php";

// define the current php filename
$thisPage = basename(__FILE__);

// define the target php filename when doing a POST
$targetPage = "../controller/updatecontrol.php";

error_log($thisPage . ": START", 0);

// load the necessary functions/files to be called here
require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once 'instring.php';
require_once '../model/get_fullname.php';
require_once '../model/get_role_desc.php';
require_once '../model/get_journal_entries.php';

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
	
	// START: Get the necessary data to display==========================================================================

	$list_journal = get_journal_entries($dbconn);
/*
	journal_row_id
	journal_ref_id
	journal_entry_date
	journal_period
	journal_posting_date
	journal_txn_type
	journal_txn_class
	journal_description
	journal_total_credit_amount
	journal_total_credit_wtax
	journal_total_credit_vat
	journal_total_debit_amount
	journal_total_debit_wtax
	journal_total_debit_vat
	journal_mark_as_void
	journal_mark_as_deleted
	journal_created_by_login_id
	journal_created_ts
	journal_modified_by_login_id
	journal_modified_ts
*/	
	$list_rows = count($list_journal);
	
	// END: Get the necessary data to display==========================================================================
	
	// setup the browser window and include the necessary CSS, Javascript and header files
?>
	<head>
		<title><?php echo $browsertitle ?></title>
		<link rel='stylesheet' type='text/css' href='css/<?php echo $cssfile ?>'>
	</head>
	<body>
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
<?php
	if (isset($_REQUEST['error_msg'])) {
?>
			<!-- START: Error message section of the page -->
			<table class='main-table'>
				<tr><td class='alert-text' width='100%'><?php echo $_REQUEST['error_msg'] ?></td></tr>
			</table>
			<!-- END: Error message section of the page -->
<?php
	}
?>
			<!-- START: Main section of the page -->

			<table class='main-table'>

				<tr><td class='main-title'><?php echo $page_menu_code ?> - G E N E R A L&nbsp;&nbsp;&nbsp;J O U R N A L&nbsp;&nbsp;&nbsp;V A L I D A T I O N</td></tr>

				<!-- START: MAIN CONTENT============================================================================================= -->

				<tr><td class='main-sub-title-left'>SELECT JOURNAL ENTRY TO VALIDATE</td></tr>

				<tr>
					<td class='main-body-text-left' height='30px'>
						<b>Search:</b>&nbsp;&nbsp;
						<input type='text' name='livesearch' id='livesearch' onkeyup='journalSearch(this.value);resetDebitCreditSection()' class='input-long'>
					</td>
				</tr>

				<!-- BEGIN: Journal HEADER section=================================================================================== -->
				<tr>
					<td>
						<div id='journal-entry-selection' class='journal-entry-selection'>
<?php 

require_once 'gen_journal_header_list.php';

?>
						</div>
					</td>
				</tr>
				<!-- END: Journal HEADER section===================================================================================== -->
				
				<tr><td class='main-sub-title-left'>JOURNAL DEBIT DETAILS</td></tr>

				<!-- START: Journal DEBIT DETAILS section============================================================================ -->
				<tr>
					<td>
						<div id='journal-dr-details-selection' class='journal-details-selection'>
						
						<!-- This is where the DEBIT details are going to be displayed -->
						
						</div>
					</td>
				</tr>
				<!-- END: Journal DEBIT DETAILS section============================================================================== -->

				<tr><td class='main-sub-title-left'>JOURNAL CREDIT DETAILS</td></tr>

				<!-- START: Journal CREDIT DETAILS section=========================================================================== -->
				<tr>
					<td>
						<div id='journal-cr-details-selection' class='journal-details-selection'>

						<!-- This is where the CREDIT details are going to be displayed -->

						</div>
					</td>
				</tr>
				<!-- END: Journal CREDIT DETAILS section============================================================================= -->

				<!-- START: Journal RETURN FOR REVISION REMARKS section=========================================================================== -->
				<tr>
					<td class='main-body-text-left'>
						<div id='journal-revision-remarks' class='journal-revision-remarks'>

						<!-- This is where the RETURN FOR REVISION REMARKS is going to be displayed -->

						<b>Remarks<b></b> (Required only if returning for revision):<br>
						<textarea name="jnl_revision_remarks" id="jnl_revision_remarks" rows="5" cols="60"></textarea>
						
						</div>
					</td>
				</tr>
				<!-- END: Journal RETURN FOR REVISION REMARKS section============================================================================= -->

				<!-- END: MAIN CONTENT=============================================================================================== -->

			</table>

			<!-- START of BOTTOM MENU BUTTONS section -->
			
			<table class='main-table'>
				<tr>
					<td class='main-title' colspan='5'>
						<input type="button" value="Validate" id="cmdValidate" class="cmdbutton2" onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'VALIDATE')">
						&nbsp;&nbsp;
						<input type="button" value="Revise" id="cmdRevise" class="cmdbutton2" onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'REVISE')">
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
		<script type='text/javascript' src='js/<?php echo $jsfile2 ?>'></script>
		<script type='text/javascript'>

			<!-- put functions here  -->
		
		</script>
	</body>
</html>

				
				
				