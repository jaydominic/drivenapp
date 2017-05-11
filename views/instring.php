<?php

function instring($strmenucodes, $menucode) {
	
	if (substr_count($strmenucodes, $menucode) > 0) {
		return true;
	} else {
		return false;
	}
	
}

?>