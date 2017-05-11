<!DOCTYPE html>
<?php
require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../model/update_password.php';

if (isset($_REQUEST['pw1'])) {
	$result = update_password($dbconn, $_REQUEST['login_id'], $_REQUEST['target_id'], $_REQUEST['pw1']);
}
?>
<html>
	<head>
		<title><?php echo $browsertitle ?></title>
		<link rel='stylesheet' type='text/css' href='css/<?php echo $cssfile ?>'>
	</head>
	<body onload='document.getElementById("pw1").focus()'>
		<form name="frmMain" id="frmMain" method="post" action="change_password.php">
			<input type='hidden' name='opr' id='opr' value='<?php echo $_REQUEST["opr"] ?>'>
			<input type='hidden' name='login_id' id='login_id' value='<?php echo $_REQUEST["login_id"] ?>'>
			<input type='hidden' name='target_id' id='target_id' value='<?php echo $_REQUEST["target_id"] ?>'>
			<table class="main-table">
				<tr>
					<td colspan="2" class="main-title"><?php echo $apptitle ?></td>
				</tr>
				<tr>
					<td colspan="2" class="main-sub-title">Change Password</td>
				</tr>
<?php 
if (isset($_REQUEST['pw1'])) {
	if ($result > 0) {
?>
				<tr>
					<td colspan="2" class="alert-text">Password has been updated</td>
				</tr>
<?php 
	} else {
?>
				<tr>
					<td colspan="2" class="alert-text">Password was NOT updated</td>
				</tr>
<?php
	}
} else {
?>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
<?php
}
?>				<tr>
					<td class="main-body-text-left">Enter Password:</td>
					<td class="main-body-text-left"><input type="password" name="pw1" id="pw1" class="input-short"></td>
				</tr>
				<tr>
					<td class="main-body-text-left">Re-enter Password:</td>
					<td class="main-body-text-left"><input type="password" name="pw2" id="pw2" class="input-short"></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" class="buttonrow">
						<input type="button" value="Submit" id="cmdSubmit" class="cmdbutton" onclick="updatePassword('<?php echo $_REQUEST["opr"] ?>')">
						&nbsp;
						&nbsp;
						<input type="button" value="Cancel" id="cmdCancel" class="cmdbutton" onclick="window.close()">
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</table>
		</form>
		<script type="text/javascript" src="js/<?php echo $jsfile ?>"></script>
	</body>
</html>
