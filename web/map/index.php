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

class Categorys {
  public static $info = array(
    "names"        => array("Building name", "Building Names", "Buildings by Name"),
    "campus"       => array("Building name", "Building Names", "Buildings by Campus"),
    "parking"      => array("Parking lots", "Parking Lots", "Parking Lots")
  );
}
$category_info = Categorys::$info;

$index = ($prefix == "ip") ? 0 : 2;

$categorys = array();
foreach($category_info as $category => $title) {
  $categorys[$category] = $title[$index];
}

if(!isset($_REQUEST['category'])) {
  $page->cache();
  require "$prefix/index.html";
} else {
  $category = $_REQUEST['category'];
  $title = $category_info[$category][2];


  if(!isset($_REQUEST['drilldown'])) {
    $places = getData();
    if($category=="names" || $category=="campus") {
      require "$prefix/$category.html";
    } else {
      require "$prefix/places.html";
    }
  } else {
    $titlebar = ucwords($category_info[$category][0]);
    $drilldown = $_REQUEST['drilldown'];
    $drilldown_title = $_REQUEST['desc'];
    $places = places_sublist($drilldown);
    require "$prefix/drilldown.html";
  }
} 



$page->output();

function places() {
  require "buildings.php";

  if($_REQUEST['category'] == 'buildings') {
    // array needs to be converted to a hash
    $places = array();
    foreach($buildings as $building_number) {
      $places[$building_number] = $building_number;
    }
  } else {
    $places = ${$_REQUEST['category']};
  }
  return $places;
}

function places_sublist($listName) {
  if($_REQUEST['category'] == 'buildings') {
    $drill = new DrillNumeralAlpha($listName, "key");
  } else {
    $drill = new DrillAlphabeta($listName, "key");
  }
  return $drill->get_list(places());
}


function drillURL($drilldown, $name=NULL) {
  $url = categoryURL() . "&drilldown=$drilldown";
  if($name) {
    $url .= "&desc=" . urlencode($name);
  }
  return $url;
}

function categoryURL($category=NULL) {
  $category = $category ? $category : $_REQUEST['category'];
  return "?category=$category";
}

function detailURL($id) {
  return "detail.php?loc=$id";
}

function searchURL() {
  return "search.php";
}

function getData($where=false) {
	if ($where) {
		$stmt = $db->prepare("SELECT * FROM Buildings WHERE ".$where." GROUP BY name ORDER BY name ASC" );
	}
	else {
		$stmt = $db->prepare("SELECT * FROM Buildings GROUP BY name ORDER BY name ASC" );
	}
	$stmt->execute();
	$result = $stmt->fetchAll();
	return $result;
}

    
?>
