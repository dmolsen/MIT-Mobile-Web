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
require_once "lib/map.lib.inc.php";

if($search_terms = $_REQUEST['filter']) {
  $results = map_search($search_terms);
  $total = count($results);
  if(count($results) == 1) {
    header("Location: " . detailURL($results[0]['id'],$results[0]['latitude'],$results[0]['longitude']));
  } else {
    require "$prefix/search.html";
    $page->output();
  }
} else {
  header("Location: ./");
}

function map_search($terms) {
  $db = db::$connection;
  $sql = "SELECT * FROM Buildings WHERE name LIKE '%".$terms."%' OR physical_address LIKE '%".$terms."%' and type != 'Parking Lot' GROUP BY name ORDER BY name DESC";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  return $result;
}
    
?>
