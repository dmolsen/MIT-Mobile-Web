<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../lib/db.php";
require_once "../page_builder/page_header.php";
require_once "../../config.gen.inc.php";
require_once "../map/lib/map.lib.inc.php";

class Categorys {
  public static $info = array(
	#"admissions"   => array("Admissions & Records", "Admissions & Records", "Admissions & Records", "Admissions"),
	"computer"     => array("Computer Lab name", "Computer Lab Names", "Computer Labs", "Computer Lab"),
	"dining"       => array("Dining location", "Dining Location Names", "Dining Locations", "Dining"),
	"health"       => array("Health Services name", "Health Services Names", "Health Services", "Health"),
    "library"      => array("Library name", "Library Names", "Libraries", "Library"),
    "prt"          => array("PRT Station", "PRT Station Names", "PRT Stations", "PRT Station"),
    "visitor"      => array("Visitor Resource Center", "Visitor Resource Center", "Visitor Resource Center", "Visitor")
  );
}

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

############################################################
### Extra functions
############################################################

function categoryURL($category=NULL) {
	$category = $category ? $category : $_REQUEST['category'];
	return "?category=$category";
}

function getData($where=false) {
	$db = db::$connection;
	if ($where) {
		$stmt = $db->prepare("SELECT * FROM Buildings WHERE ".$where." GROUP BY name ORDER BY name ASC");
	} else {
		$stmt = $db->prepare("SELECT * FROM Buildings GROUP BY name ORDER BY name ASC");
	}
	$stmt->execute();
	$result = $stmt->fetchAll();
	return $result;
}

    
?>
