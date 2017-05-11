<!DOCTYPE html>
<html>
<head>
<?php

require_once '../config.php';
require_once '../model/constants.php';
require_once '../model/dbconnect.php';
require_once '../model/get_role_menu.php';
require_once '../model/get_menu.php';
require_once 'setup_home_page.php';
require_once 'setup_main_menu.php';
require_once 'instring.php';

?>
<title><?php echo $browsertitle ?></title>
<link rel="shortcut icon" href="views/images/<?php echo $favicon ?>" type="image/x-icon">
<link rel="icon" href="views/images/<?php echo $favicon ?>" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="css/<?php echo $cssfile ?>">
</head>
<body>
<form name="frmMain" id="frmMain">
<div>
  <img src='images/<?php echo $logofile2 ?>' alt='Driven Logo' class='header-img-small' />
</div>
<hr>
<?php

$jsonstr = (isset($_REQUEST["json"]) ? $_REQUEST["json"] : null);

if ($jsonstr != NULL) {

	// store the JSON data into a hidden text field for a POST method
	echo "<input type='hidden' name='json' id='json' value='" . $jsonstr . "'>";
	
	// proceed to parse the JSON data and set up the user's home page
	if (setup_home_page($jsonstr) == false) {
		
		error_log("home.php:  setup_home_page()==false", 0);
		
	}

	if (setup_main_menu($dbconn, $jsonstr) == false) {
		
		error_log("home.php:  setup_main_menu()==false", 0);
		
	}
	
} else {
	
	error_log("home.php: json error encountered.", 0);

}

?>
</form>
<script type="text/javascript" src="js/<?php echo $jsfile ?>"></script>
</body>
</html>
