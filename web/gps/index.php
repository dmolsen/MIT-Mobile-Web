<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

## NOTE THIS IS JUST A ONE-OFF TEST AND IS NOT MEANT FOR PRODUCTION USE ##

require "../page_builder/page_header.php";

if ($_REQUEST['static']) {
	$key = "ABQIAAAAgl5MtLeiQwCMBX7FdoPP_BTfAZWzJoh_gYMfdqhKwTyraOPtpRSIZm3YBA6TbcecvlyiMX_gNejDzg";
	if ($phone == 'sp') {
		$width = '220';
		$height = '160';
	}
	else if ($phone == 'fp') {
		$width = '160';
		$height = '160';
	}
	else {
		$width = '275';
		$height = '275';
	}
        $zoom = 16;
	require "$prefix/static.html";
}
else {
	require "$prefix/index.html";
}


$page->help_off();
$page->output();

?>
