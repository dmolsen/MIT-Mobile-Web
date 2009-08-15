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
    $content = new ResultsContent("items", "map", $prefix, $phone);
    require "$prefix/search.html";
    $page->output();
  }
} else {
  header("Location: ./");
}

function map_search($terms) {
  $db = db::$connection;
  $stmt = $db->prepare("SELECT * FROM Buildings WHERE name LIKE '%".$terms."%' OR physical_address LIKE '%".$terms."%' GROUP BY name ORDER BY name ASC" );
  $stmt->execute();
  $result = $stmt->fetchAll();
  return $result;
}
    
?>
