<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../page_builder/page_header.php";

//set zoom scale
define('ZOOM_FACTOR', 16);

//set the offset parameter
define('MOVE_FACTOR', 0.04);

define('LAT', 39.634419);

define('LONG', -79.954054);

switch($phone) {
 case "sp":
  define('INIT_FACTOR', 3);
  break;

 case "fp":
  define('INIT_FACTOR', 2);
  break;
}


function determine_type() {
  $types = array(
    "G"  => "Courtyards",
    "P"  => "Parking",
    "L"  => "Landmarks"
  );

  if(preg_match("/^(P|G|L)\d+/", select_value(), $match)) {
    return array("type" => $types[ $match[1] ], "field" => "Loc_ID");
  } else {
    return array("type" => "Buildings", "field" => "facility");
  }
}

function isID($id) {
  preg_match("/^([A-Z]*)/", $id, $match);
  return $match[0];
}

function pix($label, $phone) {
  //set the resolution
  $resolution = array(
    "sp" => array("220", "160"),
    "fp" => array("160", "160")
  );
  $labels = array("x" => 0, "y" => 1);
  return $resolution[$phone][ $labels[$label] ];
} 

function mapURL() {
  return "http://maps.google.com/staticmap";
}

function imageURL($phone) {

  $query = array(
    "key"          => key, 
    "size"         => pix("x", $phone).'x'.pix("y", $phone),
    "center"       => lat().",".long(),
    "zoom"         => zoom(),
    "sensor"       => "false"
  );

  return mapURL() . '?' . http_build_query($query);
}

function zoom() {
  return isset($_REQUEST['zoom']) ? $_REQUEST['zoom'] : ZOOM_FACTOR;
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

function select_value() {
  return $_REQUEST['selectvalues'];
}

function scrollURL($dir) {
  $dir_arr = array(
    "E" => array(0,0.04),
    "W" => array(0,-0.04),
    "N" => array(0.04,0),
    "S" => array(-0.04,0)
  );
  $dir_vector = $dir_arr[$dir];
  return moveURL(long()+$dir_vector[0], lat()+$dir_vector[1], zoom());
}

function zoomInURL() {
  $zoom = zoom()+1;
  if ($zoom > 20) {
	$zoom = zoom();
  }
  return moveURL(long(), lat(), $zoom);
}

function zoomOutURL() {
  $zoom = zoom()-1;
  if ($zoom < 12) {
    $zoom = zoom();
  }
  return moveURL(long(), lat(), $zoom);
}

$tabs = new Tabs(selfURL(), "tab", array("Map"));

$tabs_html = $tabs->html();
$tab = $tabs->active(); 

function selfURL() {
  return moveURL(x_off(), y_off(), zoom());
}

function moveURL($long, $lat, $zoom) {
  $params = array(
    "zoom" => $zoom,
    "long" => $long,
    "lat" => $lat
  );
  return "detail.php?" . http_build_query($params);
}

$tab = tab();
$width = pix("x", $phone);
$height = pix("y", $phone);

if($num = $data['bldgnum']) {
  $building_title = "Building $num";
  if( ($name = $data['name']) && ($name !== $building_title) ) {
    $building_title .= " ($name)";
  }
} else {
  $building_title = $data['name'];
}

/**
 * this function makes the street address
 * more readable by google maps
 */
function cleanStreet($data) {    
  // remove things such as '(rear)' at the end of an address
  $street = preg_replace('/\(.*?\)$/', '', $data['street']);

  //remove 'Access Via' that appears at the begginning of some addresses
  return preg_replace('/^access\s+via\s+/i', '', $street);
} 

require "$prefix/detail.html";

$page->output();

?>
