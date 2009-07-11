<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "buildings_lib.php";

$buildings_xml = loadXML('xml/campus-map/buildings.xml');
$campus_map = $buildings_xml->documentElement;
$building_nodes = $buildings_xml->documentElement->getElementsByTagName('object');

//all buildings with MIT building numbers
$buildings = array();
foreach($building_nodes as $building) {
  $number_node = $building->getElementsByTagName('bldgnum');
  if($number_node->length) {
    $buildings[] = $number_node->item(0)->nodeValue;
  }
}
usort($buildings, 'id_compare');

$names = makeArray('xml/campus-map/buildings.xml');
$landmarks = makeArray('xml/campus-map/landmarks.xml');
$courts_green = makeArray('xml/campus-map/greens.xml');
$parking = makeArray('xml/campus-map/parking.xml');

//a list of all residences
$residences = array();
foreach($building_nodes as $building) {
  if(is_type($building, 'residence')) {
    $residences[ getName($building) ] = getID($building);
    foreach($building->getElementsByTagName('altname') as $node) {
      $residences[ $node->nodeValue ] = getID($building);
    }
  }
}
ksort($residences);

$rooms = find_contents('room', array('buildings'));
$food = find_contents('food', array('buildings', 'parking'));

$building_data = array();
foreach(array('buildings', 'greens', 'landmarks', 'parking') as $name) {
  $building_data = add_data($name, $building_data);
}

function add_data($name, $initial_data) {
  $xml = loadXML("xml/campus-map/$name.xml");
  $nodes = $xml->documentElement->getElementsByTagName('object');  
  foreach($nodes as $building) {
    if(!$initial_data[ getID($building) ]) { 
      $initial_data[ getID($building) ] = array();
      $initial_data[ getID($building) ]['whats_here'] = array();
    }
  
    foreach(array('name', 'street', 'viewangle', 'architect', 'bldgnum') as $field) {
      if(hasField($building, $field)) {
        $initial_data[ getID($building) ][$field] = getField($building, $field);
      }
    }

   
    //fill in the city field or set it to default of Cambridge, MA
    if(hasField($building, 'city')) {
      $initial_data[ getID($building) ]['city'] = getField($building, 'city');
    } else {
      $initial_data[ getID($building) ]['city'] = "Cambridge, MA";
    }
    
    
    //fill in the beginning of the what's here entry
    foreach($building->getElementsByTagName('contents') as $content) {
      $initial_data[ getID($building) ]['whats_here'][ getField($content, 'name') ]= True;
    }
  }
  return $initial_data;
}


foreach(array('offices', 'research') as $linkDir) {
  $links_xml = loadXML("xml/$linkDir/links.xml");
  $links = $links_xml->documentElement->getElementsByTagName('link');
  foreach($links as $link) {
    foreach($link->getElementsByTagName('location') as $location) {
      preg_match('/^([A-Z]*\d+[A-Z]*)/', $location->nodeValue, $location_match);
      $building_data[ $location_match[0] ] ['whats_here'][ getField($link, 'name') ] = True;
    }
  }
}

?>
