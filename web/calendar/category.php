<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";

require "calendar_lib.php";

//various copy includes
require_once "../../config.gen.inc.php";

$category = MIT_Calendar::Category($_REQUEST['id']);
$timeframe = isset($_REQUEST['timeframe']) ? $_REQUEST['timeframe'] : 0;
$search_terms = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : "";

if(isset($_REQUEST['filter'])) {
  $dates = SearchOptions::search_dates($timeframe);
  $events = MIT_Calendar::fullTextSearch($search_terms, $dates['start'], $dates['end'], $category);
} else {
  $today = day_info(time());
  $events = MIT_Calendar::CategoryEventsHeaders($category, $today['date']);
}

$content = new ResultsContent(
  "items", "calendar", $prefix, $phone, 
  array(
    "id" => $category->catid,
    "timeframe" => $timeframe
  )
);

$form = new CalendarForm($prefix, SearchOptions::get_options($timeframe), $category->catid);
$content->set_form($form);

require "$prefix/category.html";
$page->output();

?>
