<?php
/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";

//various copy includes
require_once "../../config.gen.inc.php";

require_once "data/data.inc.php";

if ($_REQUEST['page'] == 'events') {
	require "$prefix/events.html";
}
else if ($_REQUEST['page'] == 'map') {
	$date = $_REQUEST['date'];
	$item = (int)$_REQUEST['item'];
	$data = $events[$date][$item];
	if ($prefix == 'ip') {
		require "$prefix/detail-gmap.html";
	}
	else {
		require "$prefix/detail.html";
	}
}
else {
	require "$prefix/index.html";
}

$page->help_off();
$page->output();

?>
