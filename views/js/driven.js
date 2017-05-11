"use strict";

//===================================================================

function logoff() {
	var target_url = "../controller/logoff.php"; 
	gotoURL(target_url);
}

//===================================================================

function hidemenu() {
	var x = document.getElementById('main-menu');
	if (x.style.display === 'none') {
		x.style.display = 'block';
	} else {
		x.style.display = 'none';
	}
}

function showmenu() {
	var x = document.getElementById('main-menu');
	x.style.display = 'block';
}

//===================================================================

function redirectURL(target_url) {
	location.href = target_url;
}

function gotoURL(target_url) {
	document.getElementById("frmMain").method = "post";
	document.getElementById("frmMain").action = target_url;
	document.getElementById("frmMain").submit();
}

//===================================================================

function changeScreen(fname, mcode) {
	document.getElementById("target_page").value = fname;
	document.getElementById("menu_code").value = mcode;
	
	document.getElementById("frmMain").method = "post";
	document.getElementById("frmMain").action = "../controller/menucontrol.php";
	document.getElementById("frmMain").submit();
}

//===================================================================

function updateData(calling_page, target_url, operation) {
	
	// customize the data validation based on the calling_page value
	// MANAGE_USER_ROLES.PHP
	if (calling_page == "manage_user_roles.php") {

		if (operation == "DISABLE") {

			// prompt the user first if the operation has impact on other users access to the system
			var res = confirm("Are you sure you want to DISABLE the currently selected role?\n\nClick OK to disable the account, or CANCEL to abort the operation.");
			if (res == false) {
				return false;
			}

		}
		
		// the role description field should always have data in it
		if (operation == "UPDATE") {
			if (document.getElementById("role_desc").value == "") {
				alert("Please enter the Role Description.");
				document.getElementById("role_desc").focus();
				return false;
			}
		}

		if (operation == "INSERT") {

			// the role name should always have data in it
			if (document.getElementById("role_name_new").value == "") {
				alert("Please enter the Role Name.");
				document.getElementById("role_name_new").focus();
				return false;
			}
			
			// the role description field should always have data in it
			if (document.getElementById("role_desc_new").value == "") {
				alert("Please enter the Role Description.");
				document.getElementById("role_desc_new").focus();
				return false;
			}
		}
				
	}
	
	// MANAGE_USERS.PHP
	if (calling_page == "manage_users.php") {

		if (operation == "DISABLE") {

			// prompt the user first if the operation has impact on other users access to the system
			var res = confirm("Are you sure you want to DISABLE the selected user account?\n\nClick OK to disable the account, or CANCEL to abort the operation.");
			if (res == false) {
				return false;
			}

		}
		
		if (operation == "UPDATE") {
			if (document.getElementById("user_firstname_selected").value == "") {
				alert("First Name is required.");
				document.getElementById("user_firstname_selected").focus();
				return false;
			}
			if (document.getElementById("user_lastname_selected").value == "") {
				alert("Last Name is required.");
				document.getElementById("user_lastname_selected").focus();
				return false;
			}
			if (document.getElementById("company_list").selectedIndex == 0) {
				alert("Company Name is required.");
				document.getElementById("company_list").focus();
				return false;
			}
			if (document.getElementById("branch_list").selectedIndex == 0) {
				alert("Branch Name is required.");
				document.getElementById("branch_list").focus();
				return false;
			}
			document.getElementById("cmdModify").value = "Modify Account";
			document.getElementById("cmdSubmit").disabled = true;
		}
	}

	// GEN_JOURNAL_ENTRY.PHP
	if (calling_page == "gen_journal_entry.php") {
/*		
		if (operation == "INSERT") {
			if (document.getElementById("journal_reference_id").value == "") {
				alert("Journal reference ID is required.");
				document.getElementById("journal_reference_id").focus();
				return false;
			}
			if (document.getElementById("journal_entry_date").value == "") {
				alert("Journal entry date is required.");
				document.getElementById("journal_entry_date").focus();
				return false;
			}
			if (document.getElementById("journal_period").value == "") {
				alert("Journal period is required.");
				document.getElementById("journal_period").focus();
				return false;
			}
			if (document.getElementById("journal_posting_date").value == "") {
				alert("Journal posting date is required.");
				document.getElementById("journal_posting_date").focus();
				return false;
			}
			if (document.getElementById("journal_txn_type").selectedIndex == 0) {
				alert("Journal transaction type is required.");
				document.getElementById("journal_txn_type").focus();
				return false;
			}
			if (document.getElementById("journal_gen_desc").value == "") {
				alert("Journal description is required.");
				document.getElementById("journal_gen_desc").focus();
				return false;
			}
			if (document.getElementById("journal_txn_class").selectedIndex == 0) {
				alert("Journal transaction class is required.");
				document.getElementById("journal_txn_class").focus();
				return false;
			}
			document.getElementById("cmdSubmit").disabled = true;
		}
*/		
	}
		
	document.getElementById("operation").value = operation;
	document.getElementById("frmMain").method = "post";
	document.getElementById("frmMain").action = target_url;
	document.getElementById("frmMain").submit();
}

function setupEditMode(calling_page) {
	// MANAGE_USERS.PHP
	if (calling_page == "manage_users.php") {
		if (document.getElementById("cmdModify").value == "Modify Account") {
			document.getElementById("cmdModify").value = "Cancel Modify";
			document.getElementById("cmdSubmit").disabled = false;
			document.getElementById("cmdDisable").disabled = true;
			document.getElementById("cmdCreate").disabled = true;
			
			document.getElementById("user_firstname_selected").disabled = false;
			document.getElementById("user_middlename_selected").disabled = false;
			document.getElementById("user_lastname_selected").disabled = false;
			document.getElementById("user_fullname_selected").disabled = false;
			document.getElementById("user_email_selected").disabled = false;
			document.getElementById("company_list").disabled = false;
			document.getElementById("branch_list").disabled = false;
			document.getElementById("role_list").disabled = false;
			
			document.getElementById("user_firstname_selected").focus();
		
		} else {
		
			document.getElementById("cmdModify").value = "Modify Account";
			document.getElementById("cmdSubmit").disabled = true;
			document.getElementById("cmdDisable").disabled = false;
			document.getElementById("cmdCreate").disabled = false;
			
			document.getElementById("user_firstname_selected").disabled = true;
			document.getElementById("user_middlename_selected").disabled = true;
			document.getElementById("user_lastname_selected").disabled = true;
			document.getElementById("user_fullname_selected").disabled = true;
			document.getElementById("user_email_selected").disabled = true;
			document.getElementById("company_list").disabled = true;
			document.getElementById("branch_list").disabled = true;
			document.getElementById("role_list").disabled = true;
			
		}
	}
	
}

//===================================================================

function toggle_all(toggleFlag) {
	
	var x = document.getElementsByName("cb_menu_code[]");
	var y = document.getElementById("toggle_checkbox");

	if (toggleFlag == null) {
		// act like a on/off switch
		if (y.checked == true) {
			for(var i=0; i < x.length; i++) {
				x[i].checked = true;
			}
		} else {
			for(var i=0; i < x.length; i++) {
				x[i].checked = false;
			}
		}
	} else if (toggleFlag == 0) {
		// uncheck all the checkboxes
		for(var i=0; i < x.length; i++) {
			x[i].checked = false;
		}
		y.checked = false;
	} else if (toggleFlag == 1) {
		// check all the checkboxes
		for(var i=0; i < x.length; i++) {
			x[i].checked = true;
		}
		y.checked = true;
	}
}

function set_toggle(chkflag) {
	if (chkflag == 1) {
		document.getElementById("toggle_checkbox").checked = true;
	} else {
		document.getElementById("toggle_checkbox").checked = false;
	}
}

function updateMenuList(target_url) {
	// get the value of the selected option in the dropdown list (composed of the role_id, menu_codes respectively)
	var x = document.getElementById("role_list").selectedIndex;
	document.getElementById("role_list_index").value = x;
	var y = document.getElementById("role_list")[x].value;
	
	// split the option value and store in array
	var opt_array = y.split("|");  // values are separated by the pipe symbol
	document.getElementById("role_id_selected").value = opt_array[0];  // the first element in the array is the role id
	document.getElementById("role_name_selected").value = opt_array[1];  // the first element in the array is the role name
	document.getElementById("role_description_selected").value = opt_array[2];  // the first element in the array is the role description
	document.getElementById("role_status_selected").value = opt_array[3];  // the last element in the array is the role status
	document.getElementById("role_is_active_selected").value = opt_array[4];  // the last element in the array is the active state
	document.getElementById("role_menu_codes_selected").value = opt_array[5];  // the last element in the array is the menu codes of the role

	// submit the form
	document.getElementById("frmMain").method = "post";
	document.getElementById("frmMain").action = target_url;
	document.getElementById("frmMain").submit();
}

function updateRoleInfo() {
	var x = document.getElementById("role_list");
	var opt_string = x.options[x.selectedIndex].value;
	var opt_array = opt_string.split("|");
	
	document.getElementById("role-desc").innerHTML = opt_array[2];
	if (opt_array[3] != "Disabled") {
		document.getElementById("role-status-td").className = "main-body-text-left2";
		document.getElementById("role-status").innerHTML = "This user role is active";
	} else {
		document.getElementById("role-status-td").className = "alert-text";
		document.getElementById("role-status").innerHTML = "This user role is currently disabled";
	}
	
}

// this global array will store the previous state of checkboxes for various screens
var prevCheckboxStates = [];

function savePrevCheckboxStates() {
	var j = document.getElementsByName("cb_menu_code[]");
	var k = document.getElementById("toggle_checkbox");
	// save the state of all the menu checkboxes
	for (var i=0; i<j.length; i++) {
		prevCheckboxStates.push(j[i].checked);		
	}
	// save the state of the checkbox on/off toggle switch in the column header
	prevCheckboxStates.push(k.checked);
}

function restoreCheckboxStates() {
	var j = document.getElementsByName("cb_menu_code[]");
	var k = document.getElementById("toggle_checkbox");
	// restore the state of the menu checkboxes
	for (var i=0; i<j.length; i++) {
		j[i].checked = prevCheckboxStates[i];		
	}
	// restore the state of the checkbox on/off toggle switch in the column header
	k.checked = prevCheckboxStates[j.length];
	// clear the global array
	prevCheckboxStates.length = 0;
}

function createNewRole() {
	if (document.getElementById("cmdNewRole").value == "Create New Role") {
		document.getElementById("cmdNewRole").value = "CANCEL";
		document.getElementById("cmdSaveRole").disabled = false;
		if (document.getElementById("cmdDisable") != null) {
			document.getElementById("cmdDisable").disabled = true;
		} else {
			document.getElementById("cmdEnable").disabled = true;
		}
		document.getElementById("cmdSubmit").disabled = true;
		document.getElementById("role-list-section").style.display = 'none';
		document.getElementById("role-input-section").style.display = 'block';
		document.getElementById("role_name_new").value = "";
		document.getElementById("role-desc1").style.display = 'none';
		document.getElementById("role-desc2").style.display = 'block';
		document.getElementById("role_desc_new").value = "";
		savePrevCheckboxStates();
		toggle_all(0);
		
	} else {
		document.getElementById("cmdNewRole").value = "Create New Role";
		document.getElementById("cmdSaveRole").disabled = true;
		if (document.getElementById("cmdDisable") != null) {
			document.getElementById("cmdDisable").disabled = false;
		} else {
			document.getElementById("cmdEnable").disabled = false;
		}
		document.getElementById("cmdSubmit").disabled = false;
		document.getElementById("role-list-section").style.display = 'block';
		document.getElementById("role-input-section").style.display = 'none';
		document.getElementById("role-desc1").style.display = 'block';
		document.getElementById("role-desc2").style.display = 'none';
		restoreCheckboxStates();
	}
}

//===================================================================

function updateUserAcctData(target_url, user_id_selected, user_list_index) {
	
	// call php page that returns the user account info to manage_users.php
	document.getElementById("user_id_selected").value = user_id_selected;
	document.getElementById("user_list_index").value = user_list_index;
	
	// submit the form
	document.getElementById("frmMain").method = "post";
	document.getElementById("frmMain").action = target_url;
	document.getElementById("frmMain").submit();
}

function createUser(target_url) {

	if (document.getElementById("cmdCreate").value == "Create New User") {
	
		// clicked on CREATE NEW USER button
		
		document.getElementById("cmdCreate").value = "Cancel";
		document.getElementById("cmdSave").disabled = false;
		if (document.getElementById("cmdDisable") != null) {
			document.getElementById("cmdDisable").disabled = true;
		} else {
			document.getElementById("cmdEnable").disabled = true;
		}
		document.getElementById("cmdModify").disabled = true;
		document.getElementById("cmdSubmit").disabled = true;
		document.getElementById("change_password").value = "Set Password";
		
		document.getElementById("change_password").style.display = 'none';
		document.getElementById("new_password").style.display = '';
		document.getElementById("new_password2").style.display = '';
		document.getElementById("new_password_again").style.display = '';
		document.getElementById("pw-alert2").style.display = '';
		
		document.getElementById("user_username_selected").disabled = false;
		document.getElementById("user_firstname_selected").disabled = false;
		document.getElementById("user_middlename_selected").disabled = false;
		document.getElementById("user_lastname_selected").disabled = false;
		document.getElementById("user_fullname_selected").disabled = false;
		document.getElementById("user_email_selected").disabled = false;
		document.getElementById("company_list").disabled = false;
		document.getElementById("branch_list").disabled = false;
		document.getElementById("role_list").disabled = false;

		document.getElementById("user_username_selected").value = "";
		document.getElementById("user_firstname_selected").value = "";
		document.getElementById("user_middlename_selected").value = "";
		document.getElementById("user_lastname_selected").value = "";
		document.getElementById("user_fullname_selected").value = "";
		document.getElementById("company_list").options.selectedIndex = 0;
		document.getElementById("branch_list").options.selectedIndex = 0;
		document.getElementById("role_list").options.selectedIndex = 0;

	} else {  
		
		//  CANCEL button was clicked

		document.getElementById("cmdCreate").value = "Create New User";
		document.getElementById("cmdSave").disabled = true;
		if (document.getElementById("cmdDisable") != null) {
			document.getElementById("cmdDisable").disabled = false;
		} else {
			document.getElementById("cmdEnable").disabled = false;
		}
		document.getElementById("cmdModify").disabled = false;
		document.getElementById("cmdSubmit").disabled = true;
		document.getElementById("change_password").value = "Change Password";

		document.getElementById("change_password").style.display = '';
		document.getElementById("new_password").style.display = 'none';
		document.getElementById("new_password2").style.display = 'none';
		document.getElementById("new_password_again").style.display = 'none';
		document.getElementById("pw-alert2").style.display = 'none';
		
		document.getElementById("user_username_selected").disabled = true;
		document.getElementById("user_firstname_selected").disabled = true;
		document.getElementById("user_middlename_selected").disabled = true;
		document.getElementById("user_lastname_selected").disabled = true;
		document.getElementById("user_fullname_selected").disabled = true;
		document.getElementById("user_email_selected").disabled = true;
		document.getElementById("company_list").disabled = true;
		document.getElementById("branch_list").disabled = true;
		document.getElementById("role_list").disabled = true;

		document.getElementById("frmMain").method = "post";
		document.getElementById("frmMain").action = target_url;
		document.getElementById("frmMain").submit();
	}

}

//===================================================================

function setCompany() {
	var x = document.getElementById("company_list");
	document.getElementById("user_company_selected").value = x.options[x.selectedIndex].value;
}

function setBranch() {
	var x = document.getElementById("branch_list");
	document.getElementById("user_branch_selected").value = x.options[x.selectedIndex].value;
}

//===================================================================

function selectAll(element_id) {
	document.getElementById(element_id).select();
}

//===================================================================

var winPass = null;  // global variable to store a reference to the opened window

function changePassword(login_id, target_id) {
	if (document.getElementById("change_password").value == "Change Password") {
		// user account already exists, directly update the password
		winPass = window.open("change_password.php?opr=CHANGE&login_id=" + login_id + "&target_id=" + target_id, "changePassWin", "height=250, width=350, resizable=no, menubar=no, status=no, toolbar=no");
		return true;
	}
	if (document.getElementById("change_password").value == "Set Password") {
		// user account does not exist yet, save the password in the hidden text field
		winPass = window.open("change_password.php?opr=SET&login_id=" + login_id, "changePassWin", "height=250, width=350, resizable=no, menubar=no, status=no, toolbar=no");
		return true;
	}
}

function closePassWin() {
	winPass.close();
}

function updatePassword(opr) {
	// this function is called from PHP file change_password.php
	if (document.getElementById("pw1").value == "") {
		alert("Please enter the new password.");
		document.getelementById("pw1").value = "";
		document.getelementById("pw1").focus();
		return false;
	}
	if (document.getElementById("pw2").value == "") {
		alert("Please re-enter the new password.");
		document.getelementById("pw2").value = "";
		document.getelementById("pw2").focus();
		return false;
	}
	if (document.getElementById("pw1").value != document.getElementById("pw2").value) {
		alert("The passwords entered do not match. Please re-enter the passwords");
		document.getelementById("pw1").value = "";
		document.getelementById("pw2").value = "";
		document.getelementById("pw1").focus();
		return false;
	}
	if (opr == "CHANGE") {
		winPass.document.getElementById("frmMain").method = "post";
		winPass.document.getElementById("frmMain").action = "change_password.php";
		winPass.document.getElementById("frmMain").submit();
	}
	if (opr == "SET") {
		document.getelementById("new_password").value = winPass.document.getElementById("pw1").value;
		closePassWin();
	}
}

//===================================================================

var acctPickerWin = null;  // global variable to store the name of the parent account picker window

function pickAcct(login_username, session_id, section) {
	// alert("pickAcct()=" + section);
	var vURL = "picker_parent_account.php?login_username=" + login_username + "&session_id=" + session_id + "&section=" + section;
	acctPickerWin = window.open(vURL, "parentAcctPicker", "height=350, width=600, resizable=no, menubar=no, status=no, toolbar=no");
}

function setPickedAcct(parent_account_name, section) {
	// alert("setPickedAcct()=" + section);
	if (section == "CREDIT" || section == null) {  // CREDIT section
		window.opener.document.getElementById('parent_account').value = parent_account_name;
		window.opener.document.getElementById('parent_account_tooltip').title = parent_account_name;
		// reset the value of sub-account field
		window.opener.document.getElementById('sub_account').value = "";
		window.opener.document.getElementById('sub_account_tooltip').title = "SUB-ACCOUNT";
	} else {  // DEBIT section
		window.opener.document.getElementById('parent_account2').value = parent_account_name;
		window.opener.document.getElementById('parent_account_tooltip2').title = parent_account_name;
		// reset the value of sub-account field
		window.opener.document.getElementById('sub_account2').value = "";
		window.opener.document.getElementById('sub_account_tooltip2').title = "SUB-ACCOUNT";
	}
	window.close();
}

function closePickAcctWin() {
	acctPickerWin.close();
}

//===================================================================

var subAcctPickerWin = null;  // global variable to store the name of the parent account picker window

function pickSubAcct(login_username, session_id, parent_account, section) {
	if(section == "CREDIT") {
		if(document.getElementById("parent_account").value == "") {
			alert("Please select the parent account first.");
			return false;
		}
	} else {  // DEBIT
		if(document.getElementById("parent_account2").value == "") {
			alert("Please select the parent account first.");
			return false;
		}
	}
	var vURL = "picker_sub_account.php?login_username=" + login_username + "&session_id=" + session_id + "&parent_account=" + parent_account + "&section=" + section;
	subAcctPickerWin = window.open(vURL, "parentAcctPicker", "height=350, width=600, resizable=no, menubar=no, status=no, toolbar=no");
}

function setPickedSubAcct(sub_account_name, section) {
	// alert(section);
	if (section == "CREDIT" || section == null) {
		window.opener.document.getElementById('sub_account').value = sub_account_name;
		window.opener.document.getElementById('sub_account_tooltip').title = sub_account_name;
	} else {
		window.opener.document.getElementById('sub_account2').value = sub_account_name;
		window.opener.document.getElementById('sub_account_tooltip2').title = sub_account_name;
	}
	window.close();
}

function closeSubPickAcctWin() {
	subAcctPickerWin.close();
}

function closeAcctWindows() {
	
	// alert("acctPickerWin = " + acctPickerWin + "\n\nsubAcctPickerWin = " + subAcctPickerWin);
	
	if (acctPickerWin != null) {
		closePickAcctWin();
	}
	
	if (subAcctPickerWin != null) {
		closeSubPickAcctWin();
	}
}

//===================================================================

function addCreditRow() {
	
	// check first if all the required input is there
	if (document.getElementById("parent_account").value == "") {
		alert("Please select a parent account.");
		return false;
	}
	if (document.getElementById("sub_account").value == "") {
		alert("Please select a sub-account.");
		return false;
	}
	if (document.getElementById("journal_details").value == "") {
		alert("Please enter the credit entry details.");
		document.getElementById("journal_details").focus();
		return false;
	}
	if (isNaN(document.getElementById("journal_amt").value)==true || (parseFloat(document.getElementById("journal_amt").value) == 0)) {
		alert("Please enter the credit entry amount.");
		document.getElementById("journal_amt").select();
		document.getElementById("journal_amt").focus();
		return false;
	}
	if (document.getElementById("journal_vat_type").selectedIndex == 0) {
		alert("Please select the credit entry EWT%.");
		document.getElementById("journal_vat_type").focus();
		return false;
	}
	
	// get the current number of credit entry rows we have
	var crRowCtr = parseInt(document.getElementById("creditRowCtr").value);
	// create a clone of the hidden credit entry row node template
	var targetNode = document.getElementById("creditEntryRow");
	var newNode = targetNode.cloneNode(true);
	// make sure we give it a unique DOM id
	newNode.id = "creditEntryRow" + ++crRowCtr;  // increment first before using the value of crRowCtr
	// increment the credit entry rows counter
	document.getElementById("creditRowCtr").value = crRowCtr;
	// copy all the values of the credit entry row into the new node, and reset the data entry input fields
	
	var x = document.getElementById("parent_account");
	newNode.children[0].children[0].children[0].setAttribute("value", x.value);
	x.value = "";
	
	x = document.getElementById("sub_account");
	newNode.children[1].children[0].children[0].setAttribute("value", x.value);
	x.value = "";
	
	x = document.getElementById("journal_details");
	// newNode.children[2].children[0].setAttribute("value", x.value);
	newNode.children[2].children[0].value = x.value;
	x.value = "";
	newNode.children[2].children[0].setAttribute("id", "jnl_details" + crRowCtr);
	
	x = document.getElementById("journal_amt");
	var y = x.value; // save the journal_amt for subtotals
	newNode.children[3].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[3].children[0].setAttribute("id", "jnl_amt" + crRowCtr);
	// update the total for credit amount
	x = document.getElementById("cr_total_amt");  // get the current value of the subtotal
	x.value = parseFloat(x.value) + parseFloat(y);  // update the subtotal value
	
	x = document.getElementById("journal_wtax_type");
	newNode.children[4].children[0].setAttribute("value", x.options[x.selectedIndex].innerHTML);
	newNode.children[4].children[2].setAttribute("value", x.options[x.selectedIndex].value);
	x.selectedIndex = 0;
	newNode.children[4].children[0].setAttribute("id", "jnl_wtax_type" + crRowCtr);

	x = document.getElementById("journal_wtax");
	y = x.value;
	newNode.children[5].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[5].children[0].setAttribute("id", "jnl_wtax" + crRowCtr);
	// update the total for W/Tax amount
	x = document.getElementById("cr_total_wtax");
	x.value = parseFloat(x.value) + parseFloat(y);

	x = document.getElementById("journal_vat_type");
	newNode.children[6].children[0].setAttribute("value", x.options[x.selectedIndex].innerHTML);
	newNode.children[6].children[2].setAttribute("value", x.options[x.selectedIndex].value);
	x.selectedIndex = 0;
	newNode.children[6].children[0].setAttribute("id", "jnl_vat_type" + crRowCtr);

	x = document.getElementById("journal_vat");
	y = x.value;
	newNode.children[7].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[7].children[0].setAttribute("id", "jnl_vat" + crRowCtr);
	// update the total for VAT amount
	x = document.getElementById("cr_total_vat");
	x.value = parseFloat(x.value) + parseFloat(y);

	x = document.getElementById("journal_net");
	y = x.value;
	newNode.children[8].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[8].children[0].setAttribute("id", "jnl_net" + crRowCtr);
	// update the total for NET amount
	x = document.getElementById("cr_total_net");
	x.value = parseFloat(x.value) + parseFloat(y);	
	
	x = document.getElementById("journal_ref_doc");
	y = x.value;
	newNode.children[9].children[0].setAttribute("value", x.options[x.selectedIndex].innerHTML);
	newNode.children[9].children[2].setAttribute("value", x.options[x.selectedIndex].value);
	x.selectedIndex = 0;
	newNode.children[9].children[0].setAttribute("id", "jnl_ref_doc" + crRowCtr);
	
	newNode.children[10].children[0].setAttribute("id", "delCreditBtn" + crRowCtr);
	// attach the new node to the parent node
	targetNode.parentNode.appendChild(newNode);
	newNode.style.display = "table-row";  // make the node visible
	
}

function delCreditRow(event) {

	// get the credit values to subtract from the totals
	var cr_amt = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.children[0].value;  // value of credit amount
	var cr_wtax = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.children[0].value;  // value of W/TAX amount
	var cr_vat = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.children[0].value;  // value of VAT amount
	var cr_net = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.children[0].value;  // value of NET amount

	// update the credit totals
	document.getElementById("cr_total_amt").value = parseFloat(document.getElementById("cr_total_amt").value) - parseFloat(cr_amt);
	document.getElementById("cr_total_wtax").value = parseFloat(document.getElementById("cr_total_wtax").value) - parseFloat(cr_wtax);
	document.getElementById("cr_total_vat").value = parseFloat(document.getElementById("cr_total_vat").value) - parseFloat(cr_vat);
	document.getElementById("cr_total_net").value = parseFloat(document.getElementById("cr_total_net").value) - parseFloat(cr_net);
	
	// remove the credit row entry
	var targetChild = document.getElementById(event.currentTarget.parentNode.parentNode.id);
	var targetParent = document.getElementById(event.currentTarget.parentNode.parentNode.parentNode.id);
	targetParent.removeChild(targetChild);

	// update the credit row counter
	var crRowCtr = parseInt(document.getElementById("creditRowCtr").value);
	document.getElementById("creditRowCtr").value = --crRowCtr;
	
}

//===================================================================

function addDebitRow() {
	
	// check first if all the required input is there
	if (document.getElementById("parent_account2").value == "") {
		alert("Please select a parent account.");
		return false;
	}
	if (document.getElementById("sub_account2").value == "") {
		alert("Please select a sub-account.");
		return false;
	}
	if (document.getElementById("journal_details2").value == "") {
		alert("Please enter the debit entry details.");
		document.getElementById("journal_details2").focus();
		return false;
	}
	if (isNaN(document.getElementById("journal_amt2").value)==true || (parseFloat(document.getElementById("journal_amt2").value) == 0)) {
		alert("Please enter the debit entry amount.");
		document.getElementById("journal_amt2").select();
		document.getElementById("journal_amt2").focus();
		return false;
	}
	if (document.getElementById("journal_vat_type2").selectedIndex == 0) {
		alert("Please select the debit entry EWT%.");
		document.getElementById("journal_vat_type2").focus();
		return false;
	}
	
	// get the current number of debit entry rows we have
	var drRowCtr = parseInt(document.getElementById("debitRowCtr").value);
	// create a clone of the hidden debit entry row node template
	var targetNode = document.getElementById("debitEntryRow");
	var newNode = targetNode.cloneNode(true);
	// make sure we give it a unique DOM id
	newNode.id = "debitEntryRow" + ++drRowCtr;  // increment first before using the value of crRowCtr
	// increment the debit entry rows counter
	document.getElementById("debitRowCtr").value = drRowCtr;
	// copy all the values of the debit entry row into the new node, and reset the data entry input fields
	
	var x = document.getElementById("parent_account2");
	newNode.children[0].children[0].children[0].setAttribute("value", x.value);
	x.value = "";
	
	x = document.getElementById("sub_account2");
	newNode.children[1].children[0].children[0].setAttribute("value", x.value);
	x.value = "";
	
	x = document.getElementById("journal_details2");
	newNode.children[2].children[0].value = x.value;
	x.value = "";
	newNode.children[2].children[0].setAttribute("id", "jnl_details2" + drRowCtr);
	
	x = document.getElementById("journal_amt2");
	var y = x.value; // save the journal_amt2 for subtotals
	newNode.children[3].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[3].children[0].setAttribute("id", "jnl_amt2" + drRowCtr);
	// update the total for debit amount
	x = document.getElementById("dr_total_amt");  // get the current value of the subtotal
	x.value = parseFloat(x.value) + parseFloat(y);  // update the subtotal value
	
	x = document.getElementById("journal_wtax_type2");
	newNode.children[4].children[0].setAttribute("value", x.options[x.selectedIndex].innerHTML);
	newNode.children[4].children[2].setAttribute("value", x.options[x.selectedIndex].value);
	x.selectedIndex = 0;
	newNode.children[4].children[0].setAttribute("id", "jnl_wtax_type2" + drRowCtr);

	x = document.getElementById("journal_wtax2");
	y = x.value;
	newNode.children[5].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[5].children[0].setAttribute("id", "jnl_wtax2" + drRowCtr);
	// update the total for W/Tax amount
	x = document.getElementById("dr_total_wtax");
	x.value = parseFloat(x.value) + parseFloat(y);

	x = document.getElementById("journal_vat_type2");
	newNode.children[6].children[0].setAttribute("value", x.options[x.selectedIndex].innerHTML);
	newNode.children[6].children[2].setAttribute("value", x.options[x.selectedIndex].value);
	x.selectedIndex = 0;
	newNode.children[6].children[0].setAttribute("id", "jnl_vat_type2" + drRowCtr);

	x = document.getElementById("journal_vat2");
	y = x.value;
	newNode.children[7].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[7].children[0].setAttribute("id", "jnl_vat2" + drRowCtr);
	// update the total for VAT amount
	x = document.getElementById("dr_total_vat");
	x.value = parseFloat(x.value) + parseFloat(y);

	x = document.getElementById("journal_net2");
	y = x.value;
	newNode.children[8].children[0].setAttribute("value", x.value);
	x.value = "0.00";
	newNode.children[8].children[0].setAttribute("id", "jnl_net2" + drRowCtr);
	// update the total for NET amount
	x = document.getElementById("dr_total_net");
	x.value = parseFloat(x.value) + parseFloat(y);	
	
	x = document.getElementById("journal_ref_doc2");
	y = x.value;
	newNode.children[9].children[0].setAttribute("value", x.options[x.selectedIndex].innerHTML);
	newNode.children[9].children[2].setAttribute("value", x.options[x.selectedIndex].value);
	x.selectedIndex = 0;
	newNode.children[9].children[0].setAttribute("id", "jnl_ref_doc2" + drRowCtr);
	
	newNode.children[10].children[0].setAttribute("id", "delDebitBtn" + drRowCtr);
	// attach the new node to the parent node
	targetNode.parentNode.appendChild(newNode);
	newNode.style.display = "table-row";  // make the node visible
	
}

function delDebitRow(event) {

	// get the debit values to subtract from the totals
	var dr_amt = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.children[0].value;  // value of debit amount
	var dr_wtax = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.children[0].value;  // value of W/TAX amount
	var dr_vat = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.children[0].value;  // value of VAT amount
	var dr_net = event.currentTarget.parentNode.previousElementSibling.previousElementSibling.children[0].value;  // value of NET amount

	// update the debit totals
	document.getElementById("dr_total_amt").value = parseFloat(document.getElementById("dr_total_amt").value) - parseFloat(dr_amt);
	document.getElementById("dr_total_wtax").value = parseFloat(document.getElementById("dr_total_wtax").value) - parseFloat(dr_wtax);
	document.getElementById("dr_total_vat").value = parseFloat(document.getElementById("dr_total_vat").value) - parseFloat(dr_vat);
	document.getElementById("dr_total_net").value = parseFloat(document.getElementById("dr_total_net").value) - parseFloat(dr_net);
	
	// remove the debit row entry
	var targetChild = document.getElementById(event.currentTarget.parentNode.parentNode.id);
	var targetParent = document.getElementById(event.currentTarget.parentNode.parentNode.parentNode.id);
	targetParent.removeChild(targetChild);

	// update the debit row counter
	var drRowCtr = parseInt(document.getElementById("debitRowCtr").value);
	document.getElementById("debitRowCtr").value = --drRowCtr;
	
}

//===================================================================

function computeWTAX(vOption) {
	if (vOption =="CREDIT") {
		
		if (isNaN(document.getElementById("journal_amt").value) || (parseFloat(document.getElementById("journal_amt").value) == 0)) {
			alert("Please enter the journal amount");
			document.getElementById("journal_amt").select();
			document.getElementById("journal_amt").focus();
			return false;
		}
		var vIndex = document.getElementById("journal_wtax_type").selectedIndex;
		var wtaxrate = parseFloat(document.getElementById("journal_wtax_type").options[vIndex].value);
		var jnlamt = document.getElementById("journal_amt").value;
		if (wtaxrate > 0) {
			var wtaxamount = jnlamt * (wtaxrate / 100);
		} else {
			var wtaxamount = 0;
		}
		document.getElementById("journal_wtax").value = wtaxamount.toFixed(2);
		vIndex = document.getElementById("journal_vat_type").selectedIndex;
		var vatrate = document.getElementById("journal_vat_type").options[vIndex].value;
		if (vatrate == "EXEMPT") {
			var vatamount = 0;
		} else if (vatrate == "NONVAT") {
			var vatamount = 0;
		} else { // VATREG
			var vatamount = jnlamt / 1.12 * 0.12;
		}
		document.getElementById("journal_vat").value = vatamount.toFixed(2);
		var netamount = jnlamt - wtaxamount - vatamount;
		document.getElementById("journal_net").value = netamount.toFixed(2);

	} else {  // DEBIT
	
		if (isNaN(document.getElementById("journal_amt2").value) || (parseFloat(document.getElementById("journal_amt2").value) == 0)) {
			alert("Please enter the journal amount");
			document.getElementById("journal_amt2").select();
			document.getElementById("journal_amt2").focus();
			return false;
		}
		var vIndex = document.getElementById("journal_wtax_type2").selectedIndex;
		var wtaxrate = parseFloat(document.getElementById("journal_wtax_type2").options[vIndex].value);
		var jnlamt = document.getElementById("journal_amt2").value;
		if (wtaxrate > 0) {
			var wtaxamount = jnlamt * (wtaxrate / 100);
		} else {
			var wtaxamount = 0;
		}
		document.getElementById("journal_wtax2").value = wtaxamount.toFixed(2);
		vIndex = document.getElementById("journal_vat_type2").selectedIndex;
		var vatrate = document.getElementById("journal_vat_type2").options[vIndex].value;
		if (vatrate == "EXEMPT") {
			var vatamount = 0;
		} else if (vatrate == "NONVAT") {
			var vatamount = 0;
		} else { // VATREG
			var vatamount = jnlamt / 1.12 * 0.12;
		}
		document.getElementById("journal_vat2").value = vatamount.toFixed(2);
		var netamount = jnlamt - wtaxamount - vatamount;
		document.getElementById("journal_net2").value = netamount.toFixed(2);
	
	}
}

//===================================================================

function computeVAT(vOption) {
	if (vOption =="CREDIT") {

		if (isNaN(document.getElementById("journal_amt").value) || (parseFloat(document.getElementById("journal_amt").value) == 0)) {
			alert("Please enter the journal amount");
			document.getElementById("journal_amt").select();
			document.getElementById("journal_amt").focus();
			return false;
		}
		var vIndex = document.getElementById("journal_wtax_type").selectedIndex;
		var wtaxrate = parseFloat(document.getElementById("journal_wtax_type").options[vIndex].value);
		var jnlamt = document.getElementById("journal_amt").value;
		if (wtaxrate > 0) {
			var wtaxamount = jnlamt * (wtaxrate / 100);
		} else {
			var wtaxamount = 0;
		}
		document.getElementById("journal_wtax").value = wtaxamount.toFixed(2);
		vIndex = document.getElementById("journal_vat_type").selectedIndex;
		var vatrate = document.getElementById("journal_vat_type").options[vIndex].value;
		if (vatrate == "EXEMPT") {
			var vatamount = 0;
		} else if (vatrate == "NONVAT") {
			var vatamount = 0;
		} else { // VATREG
			var vatamount = jnlamt / 1.12 * 0.12;
		}
		document.getElementById("journal_vat").value = vatamount.toFixed(2);
		var netamount = jnlamt - wtaxamount - vatamount;
		document.getElementById("journal_net").value = netamount.toFixed(2);
		
	} else {  // DEBIT
		
		if (isNaN(document.getElementById("journal_amt2").value) || (parseFloat(document.getElementById("journal_amt2").value) == 0)) {
			alert("Please enter the journal amount");
			document.getElementById("journal_amt2").select();
			document.getElementById("journal_amt2").focus();
			return false;
		}
		var vIndex = document.getElementById("journal_wtax_type2").selectedIndex;
		var wtaxrate = parseFloat(document.getElementById("journal_wtax_type2").options[vIndex].value);
		var jnlamt = document.getElementById("journal_amt2").value;
		if (wtaxrate > 0) {
			var wtaxamount = jnlamt * (wtaxrate / 100);
		} else {
			var wtaxamount = 0;
		}
		document.getElementById("journal_wtax2").value = wtaxamount.toFixed(2);
		vIndex = document.getElementById("journal_vat_type2").selectedIndex;
		var vatrate = document.getElementById("journal_vat_type2").options[vIndex].value;
		if (vatrate == "EXEMPT") {
			var vatamount = 0;
		} else if (vatrate == "NONVAT") {
			var vatamount = 0;
		} else { // VATREG
			var vatamount = jnlamt / 1.12 * 0.12;
		}
		document.getElementById("journal_vat2").value = vatamount.toFixed(2);
		var netamount = jnlamt - wtaxamount - vatamount;
		document.getElementById("journal_net2").value = netamount.toFixed(2);

	}
	
}

