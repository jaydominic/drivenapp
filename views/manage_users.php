<!DOCTYPE html>
<html>
<?php

// RULE:  if page_menu_code is found in list_role_menus, the user can make updates

error_log("manage_users.php: START", 0);

// load the necessary functions/files to be called here
require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../views/instring.php';
require_once '../model/get_role_desc.php';
require_once '../model/get_list_roles.php';
require_once '../model/get_list_users.php';
require_once '../model/get_list_company.php';
require_once '../model/get_list_branch.php';

// define page-specific variables here
$page_menu_code = "A0037";

// define default fallback page in case of error or if user clicks on the main menu button
$homeURL = "../views/home.php";

// define the current php filename
$thisPage = "manage_users.php";

// define the current php filename
$targetPage = "../controller/updatecontrol.php";


// URL decode the JSON parameter, decode the JSON data and store into a variable
$jsonstr = json_decode($_REQUEST['json']);

// check if the user is authorized to use this page
if ((instring($jsonstr->str_menu_codes, $page_menu_code) == false) && ($jsonstr->menu_code == $page_menu_code)) {

	error_log("manage_users.php: Menu code " . $page_menu_code . " not found in str_menu_codes.", 0);
	
	echo "<script type='text/javascript' src='../views/js/" . $jsfile . "'></script>";
	echo "<form name='frmMain' id='frmMain'>";
	echo "<input type='hidden' name='json' id='json' value='" . $jsonstr . "'>";
	echo "</form>";
	echo "<script type='text/javascript'>gotoURL('" . $homeURL . "')</script>";
	exit();

} else {
	
	/*
	 * The JSON request object gets its value from function get_user() 
	 * which returns an array object in JSON format with the following fields:
	 * 
	 * login_id
	 * login_username
	 * login_lastname
	 * login_firstname
	 * login_loginrole
	 * login_role_id
	 * login_is_active
	 * login_mark_as_deleted
	 * login_status
	 * role_name
	 * role_description
	 */
	
	// get all the necessary variables from the JSON request
	$login_id = $jsonstr->login_id;
	$login_username = $jsonstr->login_username;
	$login_firstname = $jsonstr->login_firstname;
	$login_lastname = $jsonstr->login_lastname;
	$login_middlename = $jsonstr->login_middlename;
	$login_fullname = $jsonstr->login_fullname;
	$login_company = $jsonstr->login_company;
	$login_branch = $jsonstr->login_branch;
	$login_role_id = $jsonstr->login_role_id;
	$login_is_active = $jsonstr->login_is_active;
	$login_mark_as_deleted = $jsonstr->login_mark_as_deleted;
	$login_status = $jsonstr->login_status;
	$str_menu_codes = (isset($_REQUEST['str_menu_codes']) ? $_REQUEST['str_menu_codes'] : $jsonstr->str_menu_codes);
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
	
	// get the number of rows of list_roles to be used as the rows of the drop down list
	$rows1 = count($list_roles);
	
	// retrieve ALL the needed USERS and their data and store in array variables
	$list_users = get_list_users($dbconn);
	/*
	 * The get_list_users() function will return an array object containing the following fields:
	 *
	 * login_id
	 * login_username
	 * login_password
	 * login_lastname
	 * login_firstname
	 * login_middlename
	 * login_fullname
	 * login_email
	 * login_company
	 * login_branch
	 * login_role_id
	 * login_created_by_login_idw
	 * login_created_ts
	 * login_modified_by_login_id
	 * login_modified_ts
	 * login_is_active
	 * login_mark_as_deleted
	 * login_status
	 */
	
	// get the number of rows of list_users to be used for the system users section
	$rows2 = count($list_users);

	$list_company = get_list_company($dbconn);
	if (isset($list_company)) {
		$rows3 = count($list_company);
	} else {
		$rows3 = 0;
	}

	$list_branch = get_list_branch($dbconn);
	if (isset($list_branch)) {
		$rows4 = count($list_branch);
	} else {
		$rows4 = 0;
	}
	
	// set the default value of the currently selected user account to display
	$user_id_selected = (isset($_REQUEST['user_id_selected']) ? $_REQUEST['user_id_selected'] : $login_id);
	
	// specify the login_id to return a specific user account (single row array) based on the value of user_id_selected variable
	$user_selected = get_list_users($dbconn, $user_id_selected);
	
	// set this variable to the index value of the selected option in the USERS list; default index is 0 (first item on list)
	$user_list_index = (isset($_REQUEST['user_list_index']) ? $_REQUEST['user_list_index'] : 0);
	
	// use the values returned from the following variables if the request object does not contain any values yet
	$user_username_selected = $user_selected[0]['login_username'];
	$user_lastname_selected = $user_selected[0]['login_lastname'];
	$user_firstname_selected = $user_selected[0]['login_firstname'];
	$user_middlename_selected = $user_selected[0]['login_middlename'];
	$user_fullname_selected = $user_selected[0]['login_fullname'];
	$user_email_selected = $user_selected[0]['login_email'];
	$user_company_selected = $user_selected[0]['login_company'];
	$user_branch_selected = $user_selected[0]['login_branch'];
	$user_role_id_selected = $user_selected[0]['login_role_id'];
	$user_is_active_selected = $user_selected[0]['login_is_active'];
	$user_mark_as_deleted_selected = $user_selected[0]['login_mark_as_deleted'];
	$user_status_selected = $user_selected[0]['login_status'];
	
	// set the value of the hidden textboxes corresponding to the dropdown list option values
	$role_id_selected = (isset($_REQUEST['role_id_selected']) ? $_REQUEST['role_id_selected'] : $user_role_id_selected);
	
	// specify the role id to return only a specific row instead of all the roles in the table
	$list_role = get_list_roles($dbconn, $role_id_selected);
	
	// use the return values from list_role to populate the following variables if the request object does not contain any values yet
	$role_name_selected = $list_role[0]['role_name'];
	$role_description_selected = $list_role[0]['role_description'];
	$role_status_selected = $list_role[0]['role_status'];
	$role_is_active_selected = $list_role[0]['role_is_active'];
	$role_menu_codes_selected = $list_role[0]['role_menu_menu_codes'];

/*	
	error_log("manage_users.php:  user_id_selected=" . $user_id_selected, 0);
	error_log("manage_users.php:  user_list_index=" . $user_list_index, 0);
	error_log("manage_users.php:  user_username_selected=" . $user_username_selected, 0);
	error_log("manage_users.php:  user_lastname_selected=" . $user_lastname_selected, 0);
	error_log("manage_users.php:  user_firstname_selected=" . $user_firstname_selected, 0);
	error_log("manage_users.php:  user_middlename_selected=" . $user_middlename_selected, 0);
	error_log("manage_users.php:  user_fullname_selected=" . $user_fullname_selected, 0);
	error_log("manage_users.php:  user_email_selected=" . $user_email_selected, 0);
	error_log("manage_users.php:  user_company_selected=" . $user_company_selected, 0);
	error_log("manage_users.php:  user_branch_selected=" . $user_branch_selected, 0);
	error_log("manage_users.php:  user_role_id_selected=" . $user_role_id_selected, 0);
	error_log("manage_users.php:  user_is_active_selected=" . $user_is_active_selected, 0);
	error_log("manage_users.php:  user_mark_as_deleted_selected=" . $user_mark_as_deleted_selected, 0);
	error_log("manage_users.php:  user_status_selected=" . $user_status_selected, 0);
	
	error_log("manage_users.php:  role_id_selected=" . $role_id_selected, 0);
	error_log("manage_users.php:  role_name_selected=" . $role_name_selected, 0);
	error_log("manage_users.php:  role_description_selected=" . $role_description_selected, 0);
	error_log("manage_users.php:  role_status_selected=" . $role_status_selected, 0);
	error_log("manage_users.php:  role_is_active_selected=" . $role_is_active_selected, 0);
	error_log("manage_users.php:  role_menu_codes_selected=" . $role_menu_codes_selected, 0);
*/	
	
	// setup the browser window and include the necessary CSS, Javascript and header files
?>
	<head>
		<title><?php echo $browsertitle ?></title>
		<link rel='stylesheet' type='text/css' href='../views/css/<?php echo $cssfile ?>'>
	</head>
	<body onunload="closePassWin()">
		<div><img src='../views/images/<?php echo $logofile2 ?>' alt='Driven Logo' class='header-img-small' /></div>
		<hr>
		<form name='frmMain' id='frmMain'>

			<!-- START: store the variables in hidden textboxes for a POST method -->
			
			<!-- Variables used to store information about the current user -->
			<input type='hidden' name='login_id' id='login_id' value='<?php echo $login_id ?>'>
			<input type='hidden' name='login_role_id' id='login_role_id' value='<?php echo $login_role_id ?>'>
			<input type='hidden' name='user_is_active_selected' id='user_is_active_selected' value='<?php echo $user_is_active_selected ?>'>
			<input type='hidden' name='str_menu_codes' id='str_menu_codes' value='<?php echo $str_menu_codes ?>'>
			<input type='hidden' name='session_id' id='session_id' value='<?php echo $session_id ?>'>

			<!-- Variables for the user selected from the list of users -->
			<input type='hidden' name='user_id_selected' id='user_id_selected' value='<?php echo $user_id_selected ?>'>
			<input type='hidden' name='user_list_index' id='user_list_index' value='<?php echo $user_list_index ?>'>
			<input type='hidden' name='user_company_selected' id='user_company_selected' value='<?php echo $user_company_selected ?>'>
			<input type='hidden' name='user_branch_selected' id='user_branch_selected' value='<?php echo $user_branch_selected ?>'>
			<input type='hidden' name='user_status_selected' id='user_status_selected' value='<?php echo $user_status_selected ?>'>

			<!-- Variables used for the dropdown list selection -->
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
						<input type="button" value="LOGOUT" class="logoutbutton" onclick=logoff()>
					</td>
				</tr>
			</table>

			<!-- START: Main section of the page -->

			<!-- START of parent table to contain the 2 sub-tables which will appear side by side -->
			
			<table class='main-table'>
				<tr><td class='main-title' colspan='2' width='100%'><?php echo $page_menu_code ?> - M A N A G E&nbsp;&nbsp;U S E R&nbsp;&nbsp;A C C O U N T S</td></tr>
				<tr>
					<td width="50%" class='sub-table'>

						<!-- START OF LEFT PANEL (sub-table 1) -->

						<table class='main-table'>
							<tr><td class='main-sub-title-left'>A.&nbsp;&nbsp;SYSTEM USERS</td></tr>
						</table>

						<div id='userlist' class='user-list-box' align="center">
							<table class='main-table'>
								<thead>
									<tr>
										<th class='main-sub-title2' width='5%'>&nbsp;</th>
										<th class='main-sub-title2' width='15%'>User Name</th>
										<th class='main-sub-title2' width='20%'>Name</th>
										<th class='main-sub-title2' width='40%'>Role</th>
										<th class='main-sub-title2' width='20%'>Account Status</th>
									</tr>
								</thead>
								<tbody>
							
<?php 
	for($i=0; $i<$rows2; $i++) {
		$idstring = $list_users[$i]['login_id'];
?>				
									<tr>
<?php 
		if ($i == $user_list_index) {
?>				
										<td class='main-body-text-center'><input type='radio' name='login_id[]' id='uid<?php echo $idstring ?>' onclick="updateUserAcctData(`<?php echo $thisPage ?>`, <?php echo $idstring ?>, <?php echo $i ?>)" checked></td>
<?php 
		} else {
?>				
										<td class='main-body-text-center'><input type='radio' name='login_id[]' id='uid<?php echo $idstring ?>' onclick="updateUserAcctData(`<?php echo $thisPage ?>`, <?php echo $idstring ?>, <?php echo $i ?>)"></td>
<?php 
		}
?>				
										<td class='user-list-text-center'><?php echo $list_users[$i]['login_username'] ?></td>
										<td class='user-list-text-left'><?php echo $list_users[$i]['login_firstname'] . " " . $list_users[$i]['login_lastname'] ?></td>
										<td class='user-list-text-left'><?php echo get_role_desc($dbconn, $list_users[$i]['login_id']) ?></td>
										<td class='user-list-text-left'><?php echo (isset($list_users[$i]['login_mark_as_deleted']) ? "Deleted" : "Active") ?></td>
									</tr>
<?php

	}

?>
								</tbody>
							</table>
						</div>
						
						<!-- END OF LEFT PANEL (sub-table 1) -->
		
					</td>
					<td width="50%" class='sub-table'>

						<!-- START OF RIGHT PANEL (sub-table 2) -->

						<table class='main-table'>
							<tr><td class='main-sub-title-left' colspan='5'>B.&nbsp;&nbsp;USER INFORMATION</td></tr>
			
							<tr>
								<td colspan='2' class='main-body-text-right3'>Username:</td>
								<td class='main-body-text-left2' colspan='3'>
									<input type='text' class='loginuname' name='user_username_selected' id='user_username_selected' value='<?php echo $user_username_selected ?>' disabled>
									<span class='alert-text2'>(required)</span>
								</td>
							</tr>

							<!-- START: PASSWORD SECTION -->

							<tr>
								<td colspan='2' class='main-body-text-right3'>
									<span id="password-change">Password:</span>
								</td>
								<td colspan='3' class='main-body-text-left2'>
									<input type='button' name='change_password' id='change_password' value='Change Password' class="cmdbutton2" onclick="changePassword(<?php echo $login_id ?>, <?php echo $user_id_selected ?>)">
									<input type='text' name='new_password' id='new_password' class='loginpword' style='display: none;'>
									<span class='alert-text2'>(required)</span>
								</td>
							</tr>
							
							<tr>
								<td colspan='2' class='main-body-text-right3'>
									<span id='new_password2' style='display: none;'>Re-enter Password:</span>
								</td>
								<td colspan='3' class='main-body-text-left2'>
									<input type='text' name='new_password_again' id='new_password_again' class='loginpword' style='display: none;'>
									<span id='pw-alert2' class='alert-text2' style='display: none;'>(required)</span>
								</td>
							</tr>

							<!-- END: PASSWORD SECTION -->

							<tr>
								<td colspan='2' class='main-body-text-right3'>First Name:</td>
								<td class='main-body-text-left2' colspan='3'><input type='text' class='input-medium' name='user_firstname_selected' id='user_firstname_selected' value='<?php echo $user_firstname_selected ?>' disabled>
									<span class='alert-text2'>(required)</span>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='main-body-text-right3'>Middle Name:</td>
								<td class='main-body-text-left2' colspan='3'>
									<input type='text' class='input-very-short' name='user_middlename_selected' id='user_middlename_selected' value='<?php echo $user_middlename_selected ?>' disabled>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='main-body-text-right3'>Last Name:</td>
								<td class='main-body-text-left2' colspan='3'>
									<input type='text' class='input-medium' name='user_lastname_selected' id='user_lastname_selected' value='<?php echo $user_lastname_selected ?>' disabled>
									<span class='alert-text2'>(required)</span>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='main-body-text-right3'>Full Name:</td>
								<td class='main-body-text-left2' colspan='3'>
									<input type='text' class='input-long' name='user_fullname_selected' id='user_fullname_selected' value='<?php echo $user_fullname_selected ?>' disabled>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='main-body-text-right3'>E-mail:</td>
								<td class='main-body-text-left2' colspan='3'>
									<input type='text' class='input-long' name='user_email_selected' id='user_email_selected' value='<?php echo $user_email_selected ?>' disabled>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='main-body-text-right3'>Company:</td>
								<td class='main-body-text-left2' colspan='3'>
								
									<!--  select the company (since there should normally only be one company, get it from config.php parameter)  -->
			
									<select class='input-long' name='company_list' id='company_list' onchange='setCompany()' disabled>
										<option value="">&lt;select company&gt;</option>
<?php 
	for ($i=0; $i<$rows3; $i++) {
		if ($list_company[$i]['company_name'] == $user_company_selected) {
?>
										<option value='<?php echo $list_company[$i]['company_name'] ?>' selected><?php echo $list_company[$i]['company_name'] ?></option>
<?php 
		} else {
?>
										<option value='<?php echo $list_company[$i]['company_name'] ?>' selected><?php echo $list_company[$i]['company_name'] ?></option>
<?php 
		}
	}
?>
									</select>
									<span class='alert-text2'>(required)</span>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='main-body-text-right3'>Branch:</td>
								<td class='main-body-text-left2' colspan='3'>
									<select class='input-long' name='branch_list' id='branch_list' onchange='setBranch()' disabled>
										<option value="">&lt;select branch&gt;</option>
<?php 
	// select the branch for this user
	for ($i=0; $i<$rows4; $i++) {
		if ($list_branch[$i]['branch_name'] == $user_branch_selected) {
?>
										<option value='<?php echo $list_branch[$i]['branch_name'] ?>' selected><?php echo $list_branch[$i]['branch_name'] ?></option>
<?php 
		} else {
?>
										<option value='<?php echo $list_branch[$i]['branch_name'] ?>'><?php echo $list_branch[$i]['branch_name'] ?></option>
<?php 
		}
	}
?>
									</select>
									<span class='alert-text2'>(required)</span>
								</td>
							</tr>
			
							<tr>
								<td class='main-body-text-right3' colspan='2'>Role Assigned</td>
								<td class='main-body-text-left2' colspan='3'>
									<select class='input-long' name='role_list' id='role_list' onchange='updateRoleInfo()' disabled>
<?php 

	// create the dropdown list for roles and make the selected role the role of the current user
	for($i=0; $i<($rows1); $i++) {
		
		$val_string = $list_roles[$i]['role_id'] . "|" . $list_roles[$i]['role_name'] . "|" . $list_roles[$i]['role_description'] . "|" . $list_roles[$i]['role_status'] . "|" . $list_roles[$i]['role_is_active'] . "|" . $list_roles[$i]['role_menu_menu_codes'];
		
		if ($list_roles[$i]['role_id'] == $user_role_id_selected) {
			echo "<option value='" . $val_string . "' selected>" . $list_roles[$i]['role_name'] . "</option>";
		} else {
			echo "<option value='" . $val_string . "'>" . $list_roles[$i]['role_name'] . "</option>";
		}
	}
?>
									</select>
									<span class='alert-text2'>(required)</span>
								</td>
							</tr>
							<tr>
								<td class='main-body-text-right3' colspan='2'>Role Description</td>
								<td class='main-body-text-left4' colspan='3'><span id="role-desc"><?php echo $role_description_selected ?></span></td>
							</tr>
							<tr>
								<td class='main-body-text-right3' colspan='2'>Role Status</td>
<?php 
	if ($user_status_selected == "Disabled") {
		echo "<td class='alert-text' id='role-status-td' colspan='3'><span id='role-status'>This user role is currently disabled</span></td>";
	} else {
		echo "<td class='main-body-text-left4' id='role-status-td' colspan='3'><span id='role-status'>This user role is active</span></td>";
	}
?>						
							</tr>
						</table>

						<!-- END OF RIGHT PANEL (sub-table 2) -->

					</td>
				</tr>
			</table>

			<!-- END of parent table to contain the 2 sub-tables which will appear side by side -->
			
			<!-- START of BOTTOM MENU BUTTONS section -->
			
			<table class='main-table'>
				<tr>
					<td class='main-title' colspan='5'>
						<input type="button" value="Modify Account" id="cmdModify" class="cmdbutton2" onclick="setupEditMode('<?php echo $thisPage ?>')">
						&nbsp;&nbsp;
						<input type="button" value="Submit Changes" id="cmdSubmit" class="cmdbutton2" onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'UPDATE')" disabled>
						&nbsp;&nbsp;
<?php 
	if ($user_status_selected == "Disabled") {
?>
						<input type="button" value="Enable Account" id="cmdEnable" class="cmdbutton2" onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'ENABLE')">
<?php
	} else {
?>
						<input type="button" value="Disable Account" id="cmdDisable" class="cmdbutton2" onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'DISABLE')">
<?php 
	}
?>
						&nbsp;&nbsp;
						<input type="button" value="Save User" id="cmdSave"  class="cmdbutton2" onclick="updateData('<?php echo $thisPage ?>', '<?php echo $targetPage ?>', 'INSERT')" disabled>
						&nbsp;&nbsp;
						<input type="button" value="Create New User" id="cmdCreate"  class="cmdbutton2" onclick="createUser('<?php echo $thisPage ?>')">
						&nbsp;&nbsp;
						<input type="button" value="Back to Menu" id="cmdMenu"  class="cmdbutton2" onclick="gotoURL('<?php echo $homeURL ?>')">
					</td>
				</tr>
			</table>

			<!-- END: Main section of the page -->

		</form>
		<script type='text/javascript' src='../views/js/<?php echo $jsfile ?>'></script>
	</body>
<?php 

}

?>
</html>
