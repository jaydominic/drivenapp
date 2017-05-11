<form name="frmMain" id="frmMain" method="POST" action="controller/authenticate.php">

<div align="center">
<img src='views/images/driven-logo.png' class="header-img" />
</div>

<table class="outerlogintable">
<?php
	$errmsg = (isset($_REQUEST["errorMsgAuth"]) ? $_REQUEST["errorMsgAuth"] : null);
	if ($errmsg != NULL) {
?>
	<tr>
		<td class="errormessage"><?php echo $errmsg; ?></td>
	</tr>
<?php 
	}
?>
	<tr>
		<td class="innerloginbox">
			<table class="innerlogintable">
				<tr>
					<td colspan="2" class="apptitle"><?php echo $apptitle; ?></td>
				</tr>
				<tr>
					<td colspan="2" class="logintitle">L O G I N</td>
				</tr>
				<tr>
					<td class="loginbody">Username</td><td><input type="text" name="uname" class="loginuname"></td>
				</tr>
				<tr>
					<td class="loginbody">Password</td><td><input type="password" name="pword" class="loginpword"></td>
				</tr>
				<tr>
					<td colspan="2" class="buttonrow">
						<input type="submit" value="SUBMIT" class="loginbutton"><input type="reset" value="CLEAR" class="loginbutton">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
