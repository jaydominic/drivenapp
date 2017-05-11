<!DOCTYPE html>
<html>
<?php

// RULE:  if page_menu_code is found in list_role_menus, the user can make updates

error_log("manage_user_roles.php: START", 0);

// load the necessary functions/files to be called here
require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../views/instring.php';
require_once '../model/get_fullname.php';
require_once '../model/get_role_desc.php';
require_once '../model/get_role_menu.php';
require_once '../model/get_list_roles.php';
require_once '../model/get_list_menus.php';
require_once '../model/get_list_role_menus.php';

// define page-specific variables here
$page_menu_code = "A0036";

// define default fallback page in case of error or if user clicks on the main menu button
$homeURL = "../views/home.php";

// define the current php filename
$thisPage = "manage_user_roles.php";

// define the current php filename
$targetPage = "../controller/updatecontrol.php";


// URL decode the JSON parameter, decode the JSON data and store into a variable
$jsonstr = json_decode($_REQUEST['json']);

// check if the user is authorized to use this page
if ((instring($jsonstr->str_menu_codes, $page_menu_code) == false) && ($jsonstr->menu_code == $page_menu_code)) {

	error_log("manage_user_roles.php: Menu code " . $page_menu_code . " not found in str_menu_codes.", 0);
	
	echo "<script type='text/javascript' src='../views/js/" . $jsfile . "'></script>";
	echo "<form name='frmMain' id='frmMain'>";
	echo "<input type='hidden' name='json' id='json' value='" . $jsonstr . "'>";
	echo "</form>";
	echo "<script type='text/javascript'>gotoURL('" . $homeURL . "')</script>";
	exit();

} else {

	// get all the necessary variables ready
	$login_id = $jsonstr->login_id;
	$login_username = $jsonstr->login_username;
	$login_role_id = $jsonstr->login_role_id;
	$str_menu_codes = (isset($_REQUEST['str_menu_codes']) ? $_REQUEST['str_menu_codes'] : $jsonstr->str_menu_codes);
	$login_fullname = get_fullname($login_id, $dbconn);
	$login_role_desc = get_role_desc($dbconn, $login_id);
	$session_id = $jsonstr->session_id;

	// retrieve ALL the needed ROLES data and store in array variables
	$list_roles = get_list_roles($dbconn);
	/*
	 * The contents of role_list index is as follows
	 * list_roles['role_id']
	 * list_roles['role_name']
	 * list_roles['role_description']
	 * list_roles['role_status']
	 * list_roles['role_is_active']
	 * list_roles['role_menu_menu_codes']
	 */
	
	$list_menus = get_list_menus($dbconn);
	/*
	 * list_menus['menu_id']
	 * list_menus['menu_code']
	 * list_menus['menu_description']
	 * list_menus['menu_enabled']
	 * list_menus['menu_mark_as_deleted']
	 */
	
	// get the number of rows of list_roles to be used as the rows of the drop down list
	$rows1 = count($list_roles);
	
	// get the number of rows of list_menus => to be used as the rows of the rest of the table
	$rows2 = count($list_menus);
	
	// set this variable to the index value of the selected option in the drop down list
	$role_list_index = (isset($_REQUEST['role_list_index']) ? $_REQUEST['role_list_index'] : 0);

	// set the value of the hidden textboxes corresponding to the dropdown list option values
	$role_id_selected = (isset($_REQUEST['role_id_selected']) ? $_REQUEST['role_id_selected'] : $login_role_id);
	
	// specify the role id to return only a specific row instead of all the roles in the table
	$list_role = get_list_roles($dbconn, $role_id_selected);
	// use the return values from list_role to populate the following variables if the request object does not contain any values yet
	$role_name_selected = (isset($_REQUEST['role_name_selected']) ? $_REQUEST['role_name_selected'] : $list_role[0]['role_name']);
	$role_description_selected = (isset($_REQUEST['role_description_selected']) ? $_REQUEST['role_description_selected'] : $list_role[0]['role_description']);
	$role_status_selected = (isset($_REQUEST['role_status_selected']) ? $_REQUEST['role_status_selected'] : $list_role[0]['role_status']);
	$role_is_active_selected = (isset($_REQUEST['role_is_active_selected']) ? $_REQUEST['role_is_active_selected'] : $list_role[0]['role_is_active']);
	$role_menu_codes_selected = (isset($_REQUEST['role_menu_codes_selected']) ? $_REQUEST['role_menu_codes_selected'] : $list_role[0]['role_menu_menu_codes']);
	
	
	// setup the browser window and include the necessary CSS, Javascript and header files
?>
	<head>
		<title><?php echo $browsertitle ?></title>
		<link rel='stylesheet' type='text/css' href='../views/css/<?php echo $cssfile ?>'>
	</head>
	<body>
		<div><img src='../views/images/<?php echo $logofile2 ?>' alt='Driven Logo' class='header-img-small' /></div>
		<hr>
		<form name='frmMain' id='frmMain'>

			<!-- START: store the variables in hidden textboxes for a POST method  -->
			
			<!-- Variables used to store information about the current user -->
			<input type='hidden' name='login_id' id='login_id' value='<?php echo $login_id ?>'>
			<input type='hidden' name='login_role_id' id='login_role_id' value='<?php echo $login_role_id ?>'>
			<input type='hidden' name='login_username' id='login_username' value='<?php echo $login_username ?>'>
			<input type='hidden' name='login_fullname' id='login_fullname' value='<?php echo $login_fullname ?>'>
			<input type='hidden' name='str_menu_codes' id='str_menu_codes' value='<?php echo $str_menu_codes ?>'>
			<input type='hidden' name='session_id' id='session_id' value='<?php echo $session_id ?>'>
			
			<!-- Variables used for the ROLE dropdown list selection -->
			<input type='hidden' name='role_list_index' id='role_list_index' value='<?php echo $role_list_index ?>'>
			<input type='hidden' name='role_id_selected' id='role_id_selected' value='<?php echo $role_id_selected ?>'>
			<input type='hidden' name='role_name_selected' id='role_name_selected' value='<?php echo $role_name_selected ?>'>
			<input type='hidden' name='role_description_selected' id='role_description_selected' value='<?php echo $role_description_selected ?>'>
			<input type='hidden' name='role_status_selected' id='role_status_selected' value='<?php echo $role_status_selected ?>'>
			<input type='hidden' name='role_is_active_selected' id='role_is_active_selected' value='<?php echo $role_is_active_selected ?>'>
			<input type='hidden' name='role_menu_codes_selected' id='role_menu_codes_selected' value='<?php echo $role_menu_codes_selected ?>'>

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

			<!-- START: Main section of the page -->

			<div id='main-section'>
				<table class='main-table'>
					<tr><td class='main-title' colspan='5' width='100%'><?php echo $page_menu_code ?> - MANAGE USER ROLES</td></tr>
					<tr>
						<td class='main-body-text-right3' colspan='2' width='30%'>Select a role</td>
						<td class='main-body-text-left3' colspan='3' width='70%'>
							<div id='role-list-section'>
								<select name='role_list' id='role_list' onchange="updateMenuList('<?php echo $thisPage ?>')">
<?php 

	// create the dropdown list for roles and make the selected role the role of the current user
	for($i=0; $i<($rows1); $i++) {

		// if ($i == $role_list_index) {
		$val_string = $list_roles[$i]['role_id'] . "|" . $list_roles[$i]['role_name'] . "|" . $list_roles[$i]['role_description'] . "|" . $list_roles[$i]['role_status'] . "|" . $list_roles[$i]['role_is_active'] . "|" . $list_roles[$i]['role_menu_menu_codes'];
		
		if ($list_roles[$i]['role_id'] == $role_id_selected) {
			echo "<option value='" . $val_string . "' selected>" . $list_roles[$i]['role_name'] . "</option>";
		} else {
			echo "<option value='" . $val_string . "'>" . $list_roles[$i]['role_name'] . "</option>";
		}
	}
?>
								</select>
							</div>
							<div id='role-input-section' style='display: none;'>
								<input type='text' class='input-long' name='role_name_new' id='role_name_new'>
							</div>
						</td>
					</tr>
					<tr>
						<td class='main-body-text-right3' colspan='2'>Role Description</td>
						<td class='main-body-text-left3' colspan='3'>
							<div id='role-desc1'>
								<input type='text' class='role-desc' name='role_desc' id='role_desc' value='<?php echo $role_description_selected ?>'>
							</div>
							<div id='role-desc2' style='display: none;'>
								<input type='text' class='role-desc' name='role_desc_new' id='role_desc_new'>
							</div>
						</td>
					</tr>
					<tr>
						<td class='main-body-text-right3' colspan='2'>Role Status</td>
<?php 
	if ($role_status_selected != "Disabled") {
		echo "<td class='main-body-text-left4' colspan='3'>This role is active</td>";
	} else {
		echo "<td class='alert-text' colspan='3'>This role is currently disabled</td>";
	}
?>						
					</tr>
					<tr><td class='main-sub-title' colspan='5'>Menu Access for the currently selected Role</td></tr>
				</table>
			</div>
			
			<div class='role-menu-list-box'>
				<table class='main-table'>
					<tr>
<?php 
	if ($role_status_selected != "Disabled") {
?>
						<th class='main-sub-title2' width='15%'>Allow Access<br><input type='checkbox' name='toggle_checkbox' id='toggle_checkbox' onclick="toggle_all()"></th>
<?php 
	} else { 
?>
						<th class='main-sub-title2' width='15%'>Allow Access<br><input type='checkbox' name='toggle_checkbox' id='toggle_checkbox' disabled></th>
<?php
	}
?>
						<th class='main-sub-title2' width='15%'>Menu Code</th>
						<th class='main-sub-title2' width='40%'>Menu Description</th>
						<th class='main-sub-title2' width='15%'>Enabled</th>
						<th class='main-sub-title2' width='15%'>Status</th>
					</tr>
<?php
	$toggle_checkbox_ctr = 0;
	for($i=0; $i<($rows2); $i++) {
		echo "<tr>";
		if (instring($role_menu_codes_selected, $list_menus[$i]['menu_code'])==true) {
			if ($role_status_selected != "Disabled") {
				echo "<td class='main-body-text-center'><input type='checkbox' name='cb_menu_code[]' value='" . $list_menus[$i]['menu_code'] . "' checked></td>";
			} else {
				echo "<td class='main-body-text-center'><input type='checkbox' name='cb_menu_code[]' value='" . $list_menus[$i]['menu_code'] . "' checked disabled></td>";
			}
			$toggle_checkbox_ctr++;
		} else {
			if ($role_status_selected != "Disabled") {
				echo "<td class='main-body-text-center'><input type='checkbox' name='cb_menu_code[]' value='" . $list_menus[$i]['menu_code'] . "'></td>";
			} else {	
				echo "<td class='main-body-text-center'><input type='checkbox' name='cb_menu_code[]' value='" . $list_menus[$i]['menu_code'] . "' disabled></td>";
			}
		}
		echo "<td class='main-body-text-center'>" . $list_menus[$i]['menu_code'] . "</td>";
		echo "<td class='main-body-text-left'>" . $list_menus[$i]['menu_description'] . "</td>";
		echo "<td class='main-body-text-center'>" . $list_menus[$i]['menu_enabled'] . "</td>";
		echo "<td class='main-body-text-center'>" . $list_menus[$i]['menu_mark_as_deleted'] . "</td>";
		echo "</tr>";		
	}
?>
				</table>
			</div>
			
			<div id='main-section'>
				<table class='main-table'>
					<tr>
						<td class='main-title' colspan='5'>
							<input type="button" value="Submit Changes" class="cmdbutton2" name='cmdSubmit' id='cmdSubmit' onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'UPDATE')">
							&nbsp;&nbsp;
<?php 
	if ($role_status_selected != "Disabled") {
?>
							<input type="button" value="Disable Role" class="cmdbutton2" name='cmdDisable' id='cmdDisable' onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'DISABLE')">
<?php
	} else {
?>
							<input type="button" value="Enable Role" class="cmdbutton2"name='cmdEnable' id='cmdEnable' onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'ENABLE')">
<?php 
	}
?>
							&nbsp;&nbsp;
							<input type="button" value="Save New Role" class="cmdbutton2" name='cmdSaveRole' id='cmdSaveRole' onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'INSERT')" disabled>
							&nbsp;&nbsp;
							<input type='button' value='Create New Role' class='cmdbutton2' name='cmdNewRole' id='cmdNewRole' onclick="createNewRole()">
							&nbsp;&nbsp;
							<input type="button" value="Back to Menu" class="cmdbutton2" name='cmdMenu' id='cmdMenu'  onclick="gotoURL('<?php echo $homeURL ?>')">
						</td>
					</tr>
				</table>
			</div>

			<!-- END: Main section of the page -->

		
		</form>
		<script type='text/javascript' src='../views/js/<?php echo $jsfile ?>'></script>
	</body>
<?php 
	if ($toggle_checkbox_ctr == $rows2) {
		// execute a javascript function to put a check on the toggle_check checkbox
		echo "<script type='text/javascript'>set_toggle(1)</script>";
	} else {
		echo "<script type='text/javascript'>set_toggle(0)</script>";
	}

}

?>
</html>
