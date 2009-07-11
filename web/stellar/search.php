<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../page_builder/page_header.php";
require_once "../../../lib/trunk/stellar.php";
require_once "stellar_lib.php";

function selfURL() {
  $start = $_REQUEST["start"] ? (int)$_REQUEST["start"] : 0;
  $query = http_build_query(array("filter" => $_REQUEST['filter'], "start" => $start));
  return "search.php?$query";
}

$classes = StellarData::search_classes($_REQUEST['filter']);

// if exactly one class is found redirect to that
// classes detail page
if(count($classes) == 1) {
  header("Location: " . detailURL($classes[0], selfURL()));
  die();
}

$content = new ResultsContent("items", "stellar", $prefix, $phone);

require "$prefix/search.html";

$page->output();
    
?>
