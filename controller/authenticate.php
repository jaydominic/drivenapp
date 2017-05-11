<?php

require_once '../config.php';
require_once "../model/constants.php";
require_once "../model/dbconnect.php";
require_once "../model/get_user.php";
require_once "../model/log_session.php";

echo "<script ='text/javascript' src='../views/js/" . $jsfile . "'></script>";
echo "<form name='frmMain' id='frmMain'>";

if(($_REQUEST["uname"] != NULL) && ($_REQUEST["pword"] != NULL)) {
	
	// get user related data in JSON format
	$userdata = getuser($_REQUEST["uname"], $_REQUEST["pword"], $dbconn);
	
	if ($userdata == false) {
		
		$errorstring = "ERROR:  Authentication error occurred.";
		
		// Use the hidden textbox to store the return value in a POST method
		echo "<input type='hidden' name='errorMsgAuth' id='errorMsgAuth' value='" . $errorstring . "'>";
		
		error_log("authenticate.php:  " . $errorstring, 0);
		echo "<script type='text/javascript'>gotoURL('../index.php')</script>";
		exit();
		
	} else {
		
		// error_log("authenticate.php: User credentials OK. Performing urlencode()", 0);
		
		// URL encode the JSON string and send to the home.php VIEW
		//$userdata = urlencode($userdata);

		// Use the hidden textbox to store the return value in a POST method
		echo "<input type='hidden' name='json' id='json' value='" . $userdata . "'>";
		
		// do a redirect here to home.php VIEW and exit()
		// error_log("authenticate.php:  redirecting to home.php with JSON message", 0);
		echo "<script type='text/javascript'>gotoURL('../views/home.php')</script>";
		exit();
		
	}

} else {
	
	$errorstring = "ERROR:  Authentication required.";
	
	// Use the hidden textbox to store the return value in a POST method
	echo "<input type='hidden' name='errorMsgAuth' id='errorMsgAuth' value='" . $errorstring . "'>";
	
	error_log("authenticate.php:  Authentication required", 0);
	echo "<script type='text/javascript'>javascript:gotoURL('../index.php')></script>";
	
}

echo "</form>";

?>

