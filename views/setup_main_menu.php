<!DOCTYPE html>
<?php

function setup_main_menu($dbconn, $jsonstring) {

	error_log("setup_main_menu(): START", 0);
	
	// Use json_decode() function to return the JSON string as an object
	$jsonarray = json_decode($jsonstring);
	
	if (json_last_error() != JSON_ERROR_NONE) {
		error_log("setup_main_menu(): error with json_decode():  error code returned was " . strval(json_last_error()), 0);
		return false;
	}
	// json_decode() OK.  Access the elements and compose the main menu page

	// get the string representation of all the menu codes allowed for the current user	
	// error_log("setup_main_menu():  calling get_role_menu(" . $jsonarray->login_role_id . ")");
	
	$str_menu_codes = get_role_menu($jsonarray->login_role_id, $dbconn);
	
	if ($str_menu_codes==false) {
		
		error_log("setup_main_menu():  get_role_menu() returned false");
		return false;
		
	} else {
	
		// Save the menu codes in a hidden text field so it can be submitted as part of a POST
		echo "<input type='hidden' name='str_menu_codes' id='str_menu_codes' value='" . $str_menu_codes . "'>";
		echo "<input type='hidden' name='target_page' id='target_page'>";
		echo "<input type='hidden' name='menu_code' id='menu_code'>";
		
		// get all menu data needed to build the menu for the current user
		// error_log("setup_main_menu():  calling getmenu(" . $jsonarray->login_role_id . ")");
	
	}
		
	$jsonarray = json_decode(getmenu($dbconn, $jsonarray->login_role_id));
	
	//echo "var_dump(jsonarray):<br>";
	//var_dump($jsonarray);
	
	if ($jsonarray==false) {
		
		error_log("setup_main_menu():  getmenu('" . $jsonarray->login_role_id . "') returned false");
		return false;		

	} else {

		$menu_cols = 4;
		
		error_log("setup_main_menu(): json_decode OK! count(jsonarray)==" . count($jsonarray), 0);

		// Display each menu group in its own table (4 columns)
		// This is the start of the PARENT table of the main menu
		//========================================================================
		echo "<table class='menu-main'>";
		echo "<tr><td colspan='" . $menu_cols . "' class='menu-title'>M A I N &nbsp;&nbsp;M E N U</td></tr>";
		echo "<tr>";
		
		for ($col_no=1; $col_no <= $menu_cols; $col_no++) {
		
			// This is the start of the child table of the main menu
			echo "<td valign='top'><table class='menu-child-table'>";
			
			// loop through the JSON array ($jsonarray) and build the menu based on the role limitations
			for($i=0; $i<count($jsonarray); $i++) {
				if ($jsonarray[$i]->menu_column == $col_no) {
					if (instring($str_menu_codes, $jsonarray[$i]->menu_code) == true) {
	
						// display as a clickable menu item
						if ($jsonarray[$i]->menu_enabled == 'Y') {
							if ($jsonarray[$i]->menu_filename <> NULL) {
								echo "<tr><td class='" . $jsonarray[$i]->menu_group_class . "'><input type='button' class='menubutton' value='" . $jsonarray[$i]->menu_description . "' onclick='changeScreen(`" . $jsonarray[$i]->menu_filename . "`, `" . $jsonarray[$i]->menu_code . "`)'></td></tr>";
							} else {
								echo "<tr><td class='" . $jsonarray[$i]->menu_group_class . "'><input type='button' class='menubutton' value='" . $jsonarray[$i]->menu_description . "' disabled></td></tr>";
							}
						} else {
							echo "<tr><td class='" . $jsonarray[$i]->menu_group_class . "'><input type='button' class='menubutton' value='" . $jsonarray[$i]->menu_description . "' disabled></td></tr>";
						}
						
					} else {
						
						// display as a disabled menu item
						echo "<tr><td class='" . $jsonarray[$i]->menu_group_class . "'><input type='button' class='menubutton' value='" . $jsonarray[$i]->menu_description . "' disabled></td></tr>";
					
					}
				}
			}

			// This is the end of the CHILD table of the main menu
			echo "</table></td>";
			
		}
		
		// This is the end of the PARENT table of the main menu
		echo "</tr>";
		echo "</td></tr></table>";	
		
	}
	
	return true;

}

?>
