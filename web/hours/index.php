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
    "library"      => array("Library name", "Library Names", "Libraries", "Library"),
    "dining"       => array("Dining location", "Dining Location Names", "Dining Locations", "Dining"),
    "computer"     => array("Computer Lab name", "Computer Lab Names", "Computer Labs", "Computer Lab"),
    "prt"          => array("PRT Station", "PRT Station Names", "PRT Stations", "PRT Station")
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
    if($category=="names" || $category=="campus") {
      require "$prefix/$category.html";
    } else {
      $places = getData("type = '".$category_info[$category][3]."'");
      require "$prefix/places.html";
    }
  } else {
    $titlebar = ucwords($category_info[$category][0]);
    $drilldown = str_replace('%20',' ',$_REQUEST['drilldown']);
    $drilldown_title = $_REQUEST['desc'];
    if ($category=="names") {
	    if (stristr($drilldown,"-")) {
		    $sql_substr = '(';
			$sql_substr .= subSQLStrBuilder($drilldown);
			$sql_substr .= ')';
	    }
	    else {
			$sql_substr = "name LIKE \"".$drilldown."%\"";
	    }
	    $places = getData("type=\"".$category_info[$category][3]."\" and ".$sql_substr);
    }
    else if ($category=="campus") {
		$places = getData("type = '".$category_info[$category][3]."' and campus = '".$drilldown."'");
	}
    require "$prefix/drilldown.html";
  }
} 

$page->output();

############################################################
### Extra functions
############################################################

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

function getData($where=false) {
	$db = db::$connection;
        if ($where) {
		$stmt = $db->prepare("SELECT * FROM Buildings WHERE ".$where." GROUP BY name ORDER BY name ASC");
	}
	else {
		$stmt = $db->prepare("SELECT * FROM Buildings GROUP BY name ORDER BY name ASC");
	}
        $stmt->execute();
	$result = $stmt->fetchAll();
	return $result;
}

    
?>
