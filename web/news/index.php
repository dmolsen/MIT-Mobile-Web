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




// dynamic pages need to include dynamics scripts
switch($_REQUEST['news']) {

  case "wvutoday":
    $News = new WVUTodayNews();
    $section = "WVU Today";
    require "$prefix/shared.html";
    break;

  case "hsc":
	$News = new HSCNews();
    $section = "HSC";
    require "$prefix/shared.html";
    break;

  case "da":
	$News = new DANews();
	$section = "Daily Athenaeum";
    require "$prefix/shared.html";
    break;

  case "oit":
    $News = new OITNews();
    $section = "OIT";
    require "$prefix/shared.html";
    break;

  default:
    $News = new WVUTodayNews();
    require "$prefix/index.html";
    
}

$items = $News->get_feed();

function wvutodayURL() {
  return "./?news=wvutoday";
}

function hscURL() {
  return "./?news=hsc";
}

function daURL() {
  return "./?news=da";
}

function oitURL() {
  return "./?news=oit";
}

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

$page->output();
    
?>
