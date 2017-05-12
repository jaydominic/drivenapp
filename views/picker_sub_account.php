<!DOCTYPE html>
<?php
require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../model/get_session_status.php';
require_once '../model/get_list_sub_account.php';

// define the current php filename
$thisPage = basename(__FILE__);

if (!isset($_REQUEST['login_username']) || !isset($_REQUEST['session_id']) || !isset($_REQUEST['parent_account'])) {
	// incomplete parameters, do not proceed!
	error_log($thisPage . ": ERROR: Expected parameters not set. Exiting", 0);
	exit();
} else {
	
	if (!isset($_REQUEST['section']) || ($_REQUEST['section']=="CREDIT")) {
		$section = "CREDIT";
	} else {
		$section = "DEBIT";
	}
	
	$result = get_session_status($dbconn, $_REQUEST['login_username'], $_REQUEST['session_id']);
	if ($result == false || $result == 0) {
		// incomplete parameters, do not proceed!
		error_log($thisPage . ": ERROR: Invalid session or no active session. Exiting", 0);
		exit();
	}
	
	error_log($thisPage . ": parent account=" . $_REQUEST['parent_account'], 0);
	
	$list_sub_account = get_list_sub_account($dbconn, $_REQUEST['parent_account']);
	/*
	 * This function will return an array object containing the following fields:
	 *
	 * coa_sub_account
	 * coa_parent_account
	 * coa_account_no
	 * coa_group
	 * coa_type
	 */
	
	$row1 = count($list_sub_account);
}

?>
<html>
	<head>
		<title><?php echo $browsertitle ?></title>
		<link rel='stylesheet' type='text/css' href='css/<?php echo $cssfile ?>'>
	</head>
	<body>
		<form name="frmMain" id="frmMain">
			<table class="main-table">
				<tr>
					<td class="main-title"><?php echo $apptitle ?></td>
				</tr>
				<tr>
					<td>

						<div class="account-picker-table">
						<table width="100%">
							<thead>
								<tr class="main-sub-title">
									<th colspan="2">Sub-Account Selection</th>
								</tr>
							</thead>
							<tbody>
<?php 
	for ($i=0; $i<$row1; $i++) {
?>
								<tr>
									<td class="account-picker-list-items">
										<?php echo $list_sub_account[$i]['coa_sub_account'] ?>
									</td>
									<td class="account-picker-list-items">
										<input type="button" value="Select" id="cmdSelect" class="cmdbutton-small" onclick="setPickedSubAcct('<?php echo $list_sub_account[$i]['coa_sub_account'] ?>', '<?php echo $section ?>')">
									</td>
								</tr>
<?php 
	}
?>
							</tbody>
						</table>
						</div>

					</td>
				</tr>
				<tr>
					<td class="buttonrow">
						<input type="button" value="Close" id="cmdClose" class="cmdbutton2" onclick="window.close()">
					</td>
				</tr>
			</table>
		</form>
		<script type="text/javascript" src="js/<?php echo $jsfile ?>"></script>
	</body>
</html>



						
						
						