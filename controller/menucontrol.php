<!DOCTYPE html>
<html>
<?php

// error_log("menucontrol.php: START", 0);

$homeURL = "../views/home.php";

// check if we have the required data from the REQUEST object
if (!isset($_REQUEST["json"])) {
	error_log("menucontrol.php: json request is NULL.", 0);
	echo "<script type='text/javascript'>location.href='" . $homeURL . "'</script>";
	exit();
}
if (!isset($_REQUEST["target_page"])) {
	error_log("menucontrol.php: target_page not specified.", 0);
	echo "<script type='text/javascript'>location.href='" . $homeURL . "'</script>";
	exit();
}
if (!isset($_REQUEST["menu_code"])) {
	error_log("menucontrol.php: menu_code not specified.", 0);
	echo "<script type='text/javascript'>location.href='" . $homeURL . "'</script>";
	exit();
}
if (!isset($_REQUEST["str_menu_codes"])) {
	error_log("menucontrol.php: str_menu_codes request is NULL.", 0);
	echo "<script type='text/javascript'>location.href='" . $homeURL . "'</script>";
	exit();
}

// store the POST data into variables
$jsonstr = json_decode($_REQUEST['json']);
$target_page = $_REQUEST["target_page"];
$menu_code = $_REQUEST["menu_code"];
$str_menu_codes = $_REQUEST["str_menu_codes"];

// update the JSON data to include the (1) target page, (2) target menu, and (3) string of MENU CODES
$jsonstr = array('login_id' => $jsonstr->login_id, 'login_username' => $jsonstr->login_username, 
		'login_lastname' => $jsonstr->login_lastname, 'login_firstname' => $jsonstr->login_firstname, 
		'login_middlename' => $jsonstr->login_middlename,	'login_fullname' => $jsonstr->login_fullname,	
		'login_company' => $jsonstr->login_company, 'login_branch' => $jsonstr->login_branch, 
		'login_role' => $jsonstr->login_role, 'login_role_id' => $jsonstr->login_role_id, 
		'login_is_active' => $jsonstr->login_is_active, 'login_mark_as_deleted' => $jsonstr->login_mark_as_deleted, 
		'login_status' => $jsonstr->login_status, 'role_name' => $jsonstr->role_name, 'role_description' => $jsonstr->role_description,
		'session_id' => $jsonstr->session_id, 'target_page' => $target_page, 'menu_code' => $menu_code, 'str_menu_codes' => $str_menu_codes);

// convert the array into a JSON string
$jsonstr = json_encode($jsonstr);

// load the necessary functions/files to be called here
require_once '../config.php';
require_once '../views/instring.php';

// include the external javascript file
?>
<body>
<form name="frmMain" id="frmMain">
<input type="hidden" name="json" id="json" value='<?php echo $jsonstr ?>'>
<script type="text/javascript" src="../views/js/<?php echo $jsfile ?>"></script>
<?php 

// check if the str_menu_codes string contains the menu code for this PHP page
if (instring($str_menu_codes, $menu_code) == true) {
	error_log("menucontrol.php:  redirecting to menu " .$menu_code, 0);
	echo "<script type='text/javascript'>gotoURL('../views/" . $target_page . "')</script>";
} else {
	error_log("menucontrol.php: " . $menu_code . " not found in str_menu_codes.", 0);
	echo "<script type='text/javascript'>gotoURL('../views/" . $homeURL . "')</script>";
}

?>
</form>
</body>
</html>
