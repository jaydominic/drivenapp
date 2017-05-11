<!DOCTYPE html>
<html>
<?php

// define page-specific variables here
$page_menu_code = "A0012";

// define default fallback page in case of error or if user clicks on the main menu button
$homeURL = "../views/home.php";

// define the current php filename
$thisPage = "gen_journal_print.php";

// define the target php filename when doing a POST
$targetPage = "../controller/dataviewcontrol.php";


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
	$login_id = $jsonstr->login_id;
	$login_username = $jsonstr->login_username;
	$login_fullname = get_fullname($login_id, $dbconn);
	$login_role_desc = get_role_desc($dbconn, $login_id);
	
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



<?php

}

?>
		</form>
	</body>
</html>

