<!DOCTYPE html>
<?php

function setup_home_page($jsonstring) {

	// Use the JSON data to compose the personalized Home page
	// Use json_decode() function to return the JSON string as an object
	$jsonarray = json_decode($jsonstring);

	if (json_last_error() != JSON_ERROR_NONE) {
		error_log("setup_home_page(): error with json_decode():  error code returned was " . strval(json_last_error()), 0);
		return false;
	}

	// json_decode() OK.  Access the elements and compose the Home page
?> 
<!-- LOGGED IN USER INFO -->

<table class="hompagebody">
  <tr>
    <td width="50%" align="left">
      <?php $fullname = $jsonarray->login_firstname . " " . $jsonarray->login_lastname; ?>
      Current User:&nbsp;&nbsp;<b><?php echo $fullname ?></b><br />
      You are logged in as: &nbsp;<i><b><?php echo $jsonarray->login_username ?>&nbsp;&nbsp;(<?php echo $jsonarray->role_description ?>)</b></i>
      <input type="hidden" name="login_username" value="<?php echo $jsonarray->login_username ?>">
      <input type="hidden" name="login_id" value="<?php echo $jsonarray->login_id ?>">
      <input type="hidden" name="login_role_id" value="<?php echo $jsonarray->login_role_id ?>">
      <input type="hidden" name="session_id" value="<?php echo $jsonarray->session_id ?>">
    </td>
    <td width="50%" align="right">
    	<input type="button" value="LOGOUT" class="logoutbutton" onclick="logoff()">
    </td>
  </tr>
</table>
<?php
	return true;
}

?>

