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
define('ZOOM_FACTOR', 2);

switch($phone) {
 case "sp":
  define('INIT_FACTOR', 3);
  break;

 case "fp":
  define('INIT_FACTOR', 2);
  break;
}

//set the offset parameter
define('MOVE_FACTOR', 0.40);

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
  return "http://ims.mit.edu/WMS_MS/WMS.asp";
}

function imageURL($phone) {
  $bbox = bbox($phone);
  $type = determine_type();

  $query2 = array(
    "request"      => "getmap",
    "version"      => "1.1.1", 
    "width"        => pix("x", $phone),
    "height"       => pix("y", $phone),
    "selectvalues" => select_value(),
    "bbox"         => $bbox["minx"].','.$bbox["miny"].','.$bbox["maxx"].','.$bbox["maxy"],
    "layers"       => layers(),
    "selectfield"  => $type['field'],
    "selectlayer"  => $type['type']
  );

  return mapURL() . '?' . http_build_query($query2);
}

function zoom() {
  return isset($_REQUEST['zoom']) ? $_REQUEST['zoom'] : 0;
}

function x_off() {
  return isset($_REQUEST['xoff']) ? $_REQUEST['xoff'] : 0;
}

function y_off() {
  return isset($_REQUEST['yoff']) ? $_REQUEST['yoff'] : 0;
}

function tab() {
  return isset($_REQUEST['tab']) ? $_REQUEST['tab'] : "Map";
}

function select_value() {
  return $_REQUEST['selectvalues'];
}

function snippets() {
  $data = Data::$values;
  $snippets = $_REQUEST['snippets'];

  // we do not want to display snippets
  // if snippets just repeats the building number
  // or building name
  if($snippets == $data['bldgnum']) {
    return NULL;
  } 

  if($snippets == $data['name']) {
    return NULL;
  } 

  return $snippets;
}

function scrollURL($dir) {
  $dir_arr = array(
    "E" => array(1,0),
    "W" => array(-1,0),
    "N" => array(0,1),
    "S" => array(0,-1)
  );
  $dir_vector = $dir_arr[$dir];
  return moveURL(x_off()+$dir_vector[0], y_off()+$dir_vector[1], zoom());
}

function zoomInURL() {
  return moveURL(x_off()*ZOOM_FACTOR, y_off()*ZOOM_FACTOR, zoom()-1);
}

function zoomOutURL() {
  return moveURL(x_off()/ZOOM_FACTOR, y_off()/ZOOM_FACTOR, zoom()+1);
}

$tabs = new Tabs(selfURL(), "tab", array("Map"));

$tabs_html = $tabs->html();
$tab = $tabs->active(); 

function selfURL() {
  return moveURL(x_off(), y_off(), zoom());
}

function moveURL($xoff, $yoff, $zoom) {
  $params = array(
    "selectvalues" => select_value(),
    "zoom" => $zoom,
    "xoff" => $xoff,
    "yoff" => $yoff,
    "snippets" => snippets()
  );
  return "detail.php?" . http_build_query($params);
}

$selectvalue = select_value();
$photoURL = photoURL();
$tab = tab();
$width = pix("x", $phone);
$height = pix("y", $phone);
$snippets = snippets();
$types = determine_type();
$layers = layers();

$whats_here = array();
$anything_here = anything_here();

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

if(getServerBBox()) {
  require "$prefix/detail.html";
} else {
  require "$prefix/not_found.html";
}



$page->output();

?>
