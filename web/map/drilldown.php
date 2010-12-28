<?php

/**
 * Copyright (c) 2010 West Virginia University
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

$category_info = Categorys::$info;
$category = $_REQUEST['category'];
$title = $category_info[$category][2];

$titlebar = ucwords($category_info[$category][0]);
$drilldown = str_replace('%20',' ',$_REQUEST['drilldown']);
$drilldown_title = $_REQUEST['desc'];

if ($category == "names") {
	$places = MapAdapter::getPlacesByName($drilldown);
} else if ($category == "campus") {
   	$places = MapAdapter::getPlacesByCampus($drilldown);
} else if ($category == "codes") {
	$places = MapAdapter::getPlacesByCode($drilldown);
	$key = 'code'; // a hack to make sure the code listing comes up on the drilldown
}

require "templates/$prefix/drilldown.html";
$page->output();

?>