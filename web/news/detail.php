<?php

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
require_once "../../lib/simple-rss/simple-rss.inc.php";

$rss_url = $news_srcs[$_REQUEST['src']]['url'];
$feed = new SimpleRss($rss_url, 300);
$items = $feed->GetRssObject();

foreach ($items->aItems as $item) {
	$description = explode("\n",strip_tags($item->sDescription,"<a>"));
	$link = $item->sLink;
	$title = $item->sTitle;
	$date = date('M. jS @ g:ia',strtotime($item->sDate));
	
	if (stripslashes($_REQUEST['title']) == $title) {
		break;
	}
}

$paragraphs = array();
foreach($description as $paragraph) {
  if($paragraph) {
    $paragraphs[] = str_replace('Read more ...','',$paragraph);
  }
}

$read_more = $news_srcs[$_REQUEST['src']]['read_more'];
$section = $news_srcs[$_REQUEST['src']]['title'];

require "templates/$prefix/detail.html";
$page->output();
    
?>
