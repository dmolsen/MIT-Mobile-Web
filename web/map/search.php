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
require_once "lib/map.lib.inc.php";

if($search_terms = $_REQUEST['filter']) {
  $results = map_search($search_terms);
  $total = count($results);
  if(count($results) == 1) {
    header("Location: " . detailURL($results[0]['id'],$results[0]['latitude'],$results[0]['longitude']));
  } else {
    require "templates/$prefix/search.html";
    $page->output();
  }
} else {
  header("Location: ./");
}

function map_search($terms) {
  $db = new db;
  $sql = "SELECT * FROM Buildings WHERE (name LIKE '%".$terms."%' OR physical_address LIKE '%".$terms."%' OR code LIKE '%".$terms."%') and (type != 'Parking Lot' AND type != 'Public Parking') GROUP BY name ORDER BY name ASC";
  $stmt = $db->connection->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  return $result;
}
    
?>
