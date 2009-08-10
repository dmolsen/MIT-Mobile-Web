<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../page_builder/page_header.php";
require_once "../../lib/rss_services.php";
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

// dynamic pages need to include dynamics scripts
switch($_REQUEST['news']) {

  // hard coding the cases. i probably should not be using a switch
  case "wvutoday":
  case "hsc":
  case "da":
  case "oit":
    $rss_url = $news_srcs[$_REQUEST['news']]['url'];
    $section = $news_srcs[$_REQUEST['news']]['title'];
    $key = $_REQUEST['news'];
    $shared = true;
    break;

  default:
    $rss_url = $news_srcs['wvutoday']['url'];
    $section = $news_srcs['wvutoday']['title'];
    $key = 'wvutoday';
    $shared = false;
 
}

$News = new RSS();
$items = $News->get_feed($rss_url);

if ($shared == true) {
  require "$prefix/shared.html";
}
else {
  require "$prefix/index.html";
}

function detailURL($title,$src) {
  return "detail.php?title=$title&src=$src";
}

function is_long_text($item) {
  return is_long_string($item['text']);
}

function summary($item) {
  return summary_string(str_replace('Read more ...','',$item['text']));
}

function full($item) {
  return $item['text'];
}

$page->output();
    
?>
