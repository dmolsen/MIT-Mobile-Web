<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../page_builder/page_header.php";
require_once "../../config.gen.inc.php";
require_once "data/teams.data.inc.php";

if ($_REQUEST['team']) {
	require "$prefix/detail.html";
}
else {	
	require "$prefix/list.html";
}

$page->cache();
$page->output();

function teamURL($team) {
  return "teams.php?team=$team";
}

?>
