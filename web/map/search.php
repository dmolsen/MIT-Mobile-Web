<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// sets up adapter class
$adapter = ModuleAdapter::find();
require_once "adapters/".$adapter."/adapter.php";

// libs
require_once "lib/map.lib.inc.php";

$results = MapAdapter::searchPlaces($_REQUEST['filter']);
$total = count($results);
if(count($results) == 1) {
	$result = $results[0];
	header("Location: " . detailURL($result['id'],$result['latitude'],$result['longitude']));
} else {
	require "templates/$prefix/search.html";
	$page->output();
}

?>
