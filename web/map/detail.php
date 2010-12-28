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

define('ZOOM', 16);
define('LAT',  $latitude);
define('LONG', $longitude);
define('MAPTYPE', "roadmap");
define('MOBILEMAP',$mobilemap);

// tabs are only "used" on the basic template and not really then either
$tabs = new Tabs(selfURL(), "tab", array("Map"));
$tabs_html = $tabs->html();
$tab = $tabs->active();

$tab = tab();
$width = pix("x", $prefix);
$height = pix("y", $prefix);

if ($_REQUEST['loc']) { 
	$places = MapAdapter::getPlace($_REQUEST['loc']);
	$place = $places[0];
}

$parent = false;
if ($place['parent'] != '') {
	$places = MapAdapter::getParent($place['parent']);
	$parent_data = $places[0];
	$parent = true;
}

require "templates/$prefix/detail.html";

$page->output();

###################################################################################
### The functions to get place detail to work, primarily for the basic template
###################################################################################

function pix($label, $prefix) {
  //set the resolution
  $resolution = array(
    "basic" => array("220", "160")
  );
  $labels = array("x" => 0, "y" => 1);
  return $resolution[$prefix][$labels[$label]];
} 

function mapURL() {
  return "http://maps.google.com/maps/api/staticmap";
}

function imageURL($prefix) {

  $query = array(
    "maptype"      => mapType(),
    "size"         => pix("x", $prefix).'x'.pix("y", $prefix),
    "center"       => lat().",".long(),
    "zoom"         => zoom(),
    "sensor"       => "false",
    "markers"      => marker(),
    "mobile"	   => MOBILEMAP
  );

  return mapURL() . '?' . urldecode(http_build_query($query));
}

function maptype() {
  return isset($_REQUEST['maptype']) ? $_REQUEST['maptype'] : MAPTYPE;
}

function zoom() {
  return isset($_REQUEST['zoom']) ? $_REQUEST['zoom'] : ZOOM;
}

function long() {
  return isset($_REQUEST['long']) ? $_REQUEST['long'] : LONG;
}

function lat() {
  return isset($_REQUEST['lat']) ? $_REQUEST['lat'] : LAT;
}

function tab() {
  return isset($_REQUEST['tab']) ? $_REQUEST['tab'] : "Map";
}

function marker() {	
  
  global $mobile_web_addy, $theme, $marker_types;

  if ((int)$_REQUEST['loc'] != 0) {
    
	$places = MapAdapter::getPlace($_REQUEST['loc']);
	$place = $places[0];
	
	$lat = $place['latitude'];
	$long = $place['longitude'];
	#$icon = "icon:http://".$mobile_web_addy."/themes/".$theme."/webkit/images/markers/".$marker_types[$place['type']].".png";
	$icon = "icon:http://".$mobile_web_addy."/map/templates/webkit/images/markers/".$marker_types[$place['type']].".png";
	return $icon."|".$lat.",".$long;
  }
  else if ($_REQUEST['all']) {
	
	// WTF does this do?
	$db = new db;
	$stmt =& $db->connection->prepare("SELECT * FROM Buildings WHERE type = ".$_REQUEST['all']);
	$result = $stmt->execute();
	$results = $result->fetchAll();
    $markers = "";
    foreach ($results as $result) {
	 	$lat = $data[0]['latitude'];
		$long = $data[0]['longitude'];
		$marker = marker_type($data[0]['type']);
		$markers .= $lat.",".$long.",".$marker."|";
    }
	return $markers;
  }
}

function select_value() {
  return $_REQUEST['selectvalues'];
}

function scrollURL($dir) {
  $move = array(
    "12" => 0.02500,
    "13" => 0.01250,
    "14" => 0.00625,
    "15" => 0.00300,
    "16" => 0.00150,
    "17" => 0.00075
  );
  
  if ($dir == "E") {
    return moveURL(long()+$move[zoom()], lat(), zoom(),maptype());
  } else if ($dir == "W") {
    return moveURL(long()-$move[zoom()], lat(), zoom(),maptype());
  } else if ($dir == "N") {
    return moveURL(long(),lat()+$move[zoom()], zoom(),maptype());
  } else {
    return moveURL(long(),lat()-$move[zoom()], zoom(),maptype());
  }
}

function zoomInURL() {
  $zoom = zoom()+1;
  if ($zoom > 17) {
	$zoom = zoom();
  }
  return moveURL(long(), lat(), $zoom, maptype());
}

function zoomOutURL() {
  $zoom = zoom()-1;
  if ($zoom < 12) {
    $zoom = zoom();
  }
  return moveURL(long(), lat(), $zoom, maptype());
}

function mapTypeURL($type) {
  return moveURL(long(), lat(), zoom(), $type);
}

function selfURL() {
  return moveURL(long(), lat(), zoom(), maptype());
}

function moveURL($long, $lat, $zoom, $maptype) {
  $params = array(
    "zoom" => $zoom,
    "long" => $long,
    "lat" => $lat,
    "maptype" => $maptype,
    "loc" => (int)$_REQUEST['loc']
  );
  return "detail.php?" . http_build_query($params);
}

?>
