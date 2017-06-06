<?php

//get the q parameter from URL
$q=$_GET["q"];

require_once 'constants.php';
require_once 'dbconnect.php';
require_once 'get_journal_entries.php';

$thisPage = basename(__FILE__);

error_log($thisPage . ": START->q=" . $q, 0);

if (strlen(trim($q))>0) {  // search parameter present, show only matching records

	$list_journal = get_journal_entries($dbconn, NULL, $q);

} else {  // no search parameter, show all records

	$list_journal = get_journal_entries($dbconn);
	
}

$list_rows = count($list_journal);

error_log($thisPage . ": list_rows=" . count($list_rows), 0);

if ($list_rows > 0) {  // matching records found
	
	require_once '../views/gen_journal_header_list.php';
	
}

error_log($thisPage . ": END", 0);

?>

