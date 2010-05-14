<?

/**
 * Copyright (c) 2009 West Virginia University
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
require_once "../../lib/rss_services.php";

$News = new RSS();
$items = $News->get_feed($news_srcs[$_REQUEST['src']]['url']);
$title = stripslashes($_REQUEST['title']);
$text = explode("\n", $items[$title]['text']);
$link = $items[$title]['link'];
$read_more = $news_srcs[$_REQUEST['src']]['read_more'];
$section = $news_srcs[$_REQUEST['src']]['title'];
$paragraphs = array();
foreach($text as $paragraph) {
  if($paragraph) {
    $paragraphs[] = str_replace('Read more ...','',$paragraph);
  }
}

$long_date = str_replace(' 0:00:00','',date("l, F j, Y G:i:s", $items[$title]['unixtime']));

require "templates/$prefix/detail.html";
$page->output();
    
?>
