<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


function loadXML($filename) {
  $xml_obj = new DOMDocument('1.0', 'iso-8859-1');
  $xml_txt = file_get_contents($filename);
  $xml_obj->load($filename);
  return $xml_obj;
}

function makeArray($filename) {
  $xml_obj = loadXML($filename);
  $objects = $xml_obj->documentElement->getElementsByTagName('object');

  //all buildings by names
  $names = array();
  foreach($objects as $object) {
    $names[ getName($object) ] = getID($object);
  }
  ksort($names);
  return $names;
}
  
function getName($object) {
    return $object->getElementsByTagName('name')->item(0)->nodeValue;
}

function getID($object) {
    return substr($object->getAttribute('id'), 7);
}

function id_compare($id1, $id2) {
  preg_match('/^([A-Z]*)(\d+)([A-Z]*)/', $id1, $match1);
  preg_match('/^([A-Z]*)(\d+)([A-Z]*)/', $id2, $match2);

  if($match1[1] > $match2[1]) {
    return 1;
  }

  if($match2[1] > $match1[1]) {
    return -1;
  }

  if((int)$match1[2] > (int)$match2[2]) {
    return 1;
  }

  if((int)$match1[2] < (int)$match2[2]) {
    return -1;
  }

  if($match1[3] > $match2[3]) {
    return 1;
  }

  if($match2[3] > $match1[3]) {
    return -1;
  }

  return 0;
}

function is_type($building, $type) {
  foreach($building->getElementsByTagName('category') as $node) {
    if($node->nodeValue == $type) {
      return true;
    }
  }
  return false;
}

function find_contents($type, $xmlfiles) {
  $found = array();
  foreach($xmlfiles as $fileName) {
    $buildings_xml = loadXML("xml/campus-map/$fileName.xml");
    $building_nodes = $buildings_xml->documentElement->getElementsByTagName('object');

    foreach($building_nodes as $building) {
      foreach($building->getElementsByTagName('contents') as $content) {
        if(is_type($content, $type)) {
          $found[ getName($content) ] = getID($building);
          foreach($content->getElementsByTagName('altname') as $node) {
            $found[ $node->nodeValue ] = getID($building);
          }
        }
      }
    }
  }
  ksort($found);
  return $found;
}

function hasField($node, $field) {
  return ($node->getElementsByTagName($field)->length > 0);
}

function getField($node, $field) {
  return $node->getElementsByTagName($field)->item(0)->nodeValue;
}

?>
