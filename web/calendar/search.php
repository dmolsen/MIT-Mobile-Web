<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
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

// sets up adapter class
$adapter = ModuleAdapter::find();
require_once "adapters/".$adapter."/adapter.php";

// libs
require_once "lib/calendar.lib.php";
require_once "lib/textformat.lib.php";

$search_terms = $_REQUEST['filter'];

$search_options = SearchOptions::get_options();

$timeframe = isset($_REQUEST['timeframe']) ? $_REQUEST['timeframe'] : 0;
$dates = SearchOptions::search_dates($timeframe);

$eventFeed = CalendarAdapter::searchEvents($search_terms,$dates['start'],$dates['end']);

require "templates/$prefix/search.html";
$page->output();

?>
