<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";
require "../../../lib/trunk/mit_calendar.php";

require "calendar_lib.php";

$search_terms = $_REQUEST['filter'];

$timeframe = isset($_REQUEST['timeframe']) ? $_REQUEST['timeframe'] : 0;
$dates = SearchOptions::search_dates($timeframe);
$events = MIT_Calendar::fullTextSearch($search_terms, $dates['start'], $dates['end']);

$content = new ResultsContent("items", "calendar", $prefix, $phone, array("timeframe" => $timeframe));

$form = new CalendarForm($prefix, SearchOptions::get_options($timeframe));
$content->set_form($form);

require "$prefix/search.html";
$page->output();

?>
