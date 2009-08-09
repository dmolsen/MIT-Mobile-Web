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

$News = new RSS();
$items = $News->get_feed($news_srcs[$_REQUEST['src']]['url']);
$title = $_REQUEST['title'];
$text = explode("\n", $items[$title]['text']);
$link = $items[$title]['link'];
$read_more = $news_srcs[$_REQUEST['src']]['read_more'];
$section = $news_srcs[$_REQUEST['src']]['title'];
$paragraphs = array();
foreach($text as $paragraph) {
  if($paragraph) {
    $paragraphs[] = $paragraph;
  }
}


$long_date = str_replace(' 0:00:00','',date("l, F j, Y G:i:s", $items[$title]['unixtime']));

require "$prefix/detail.html";
$page->output();
    
?>
