<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../page_builder/page_header.php";
require_once "../../lib/rss_services.php";
require_once "../../config.gen.inc.php";

$News = new News();
$items = $News->get_feed();

function detailURL($title) {
  return "detail.php?title=$title";
}

function is_long_text($item) {
  return is_long_string($item['text']);
}

function summary($item) {
  return summary_string($item['text']);
}

function full($item) {
  return $item['text'];
}

require "$prefix/index.html";

$page->output();
    
?>
