<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// libs - need to require first because data.inc.php uses functions
require_once "../../lib/simple-rss/simple-rss.inc.php";
require_once "lib/textformat.lib.php";

// various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

// records stats
require_once "../page_builder/page_header.php";

$emergencies = False;

if ($show_rss == true) {
	$feed = new SimpleRss($emergency_rss_url, 60);
	$emergencies = $feed->GetRssObject();
}

// Default to showing emergency items for two days after they are posted
function display_emergency($item, $window = 172800) {
	$time = strtotime($item->sDate) + $window;
	$current_time = time();

	return ($time > $current_time);
}

function format_emergency_date($date, $format = 'M. jS @ g:ia') {
	return date($format, strtotime($date));
}

if(isset($_REQUEST['contacts'])) {
  require "templates/$prefix/contacts.html";
} else if (isset($_REQUEST['extra'])) {
  require "templates/$prefix/extra.html";
} else if (isset($_REQUEST['residence'])) {
  require "templates/$prefix/residence.html";
} else if (isset($_REQUEST['schools'])) {
  require "templates/$prefix/schools.html";
} else {
  require "templates/$prefix/index.html";
}

$page->output();

?>
