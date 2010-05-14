<?

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

// libs
require_once "lib/textformat.lib.php";
require_once "lib/db.lib.php";
require_once "../map/lib/map.lib.inc.php";

$category_info = Categorys::$info;

$index = ($prefix == "iphone") ? 0 : 2;

$categorys = array();
foreach($category_info as $category => $title) {
	$categorys[$category] = $title[$index];
}

if(!isset($_REQUEST['category'])) {
	$page->cache();
	require "templates/$prefix/index.html";
} else {
	$category = $_REQUEST['category'];
	$title = $category_info[$category][2];

	if ($category=="visitor") {
		$places = getData("subtype = 'Visitor'");	
	}
	else if ($category=="rec") {
		$places = getData("subtype = 'Rec'");	
	}
	else if ($category=="admissions") {
		$places = getData("subtype = 'Admissions'");	
	}
	else if ($category=="health") {
		$places = getData("subtype = 'Health'");	
	}
	else {
		$places = getData("type = '".$category_info[$category][3]."'");
	}

	require "templates/$prefix/places.html";
} 

$page->output();
    
?>
