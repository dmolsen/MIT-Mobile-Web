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

$News = new RSS();
$items = $News->get_feed('http://157.182.32.8/u92/u92.xml');
$title = $_REQUEST['title'];
$text = explode("\n", $items[$title]['text']);
$paragraphs = array();
foreach($text as $paragraph) {
  if($paragraph) {
    $paragraphs[] = str_replace('Read more ...','',$paragraph);
  }
}

$long_date = str_replace(' 0:00:00','',date("l, F j, Y G:i:s", $items[$title]['unixtime']));

require "$prefix/article.html";
$page->output();
    
?>
