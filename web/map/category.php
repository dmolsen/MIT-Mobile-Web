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

if ($category == "campus") {
	// this is a WVU-specific listing since we have three "campuses" in one town
	require "templates/$prefix/campus.html";
}
else if (($category == "names") || ($category == "codes")) {
    require "templates/$prefix/category.html";
} else if ($category == "wifi") {
	$places = MapAdapter::getPlacesByWiFi();
	require "templates/$prefix/places.html";
} else {
	$type = $category_info[$category][3];
	$places = MapAdapter::getPlacesByType($type);
	require "templates/$prefix/places.html";
}

$page->output();

?>