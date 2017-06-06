<?php

//get the q parameter from URL
$q=$_GET["q"];

require_once '../model/constants.php';
require_once '../model/dbconnect.php';

$thisPage = basename(__FILE__);

$hint = "";

if (strlen(trim($q))>0) {

	error_log($thisPage . ": START->q=" . $q, 0);
	
	$mysqli = dbconnect($dbconn);
	$strsql = "SELECT journal_ref_id FROM tbl_journal_entry_header WHERE journal_ref_id LIKE '" . trim($q). "%' ORDER BY journal_ref_id";
	$result = $mysqli->query($strsql);
	if ($result->num_rows > 0) {  // matching records found
		
		error_log($thisPage . ": result=" . count($result), 0);
		
		while ($rows = $result->fetch_assoc()) {
			
			if ($hint == "") {
			
				$hint = $rows['journal_ref_id'];
			
			} else {
			
				$hint = $hint . "<br>" . $rows['journal_ref_id'];
			
			}
			error_log($thisPage . ": hint=" . $hint, 0);
		}
	
	} else {  // no records found
	
		$hint = "";
	
	}
	$mysqli->close();
}
error_log($thisPage . ": END=>hint=" . $hint, 0);
echo $hint;

?>

