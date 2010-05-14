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

//defines all the variables related to being today
require_once "lib/calendar.lib.php";
require_once "lib/textformat.lib.php";

$today = day_info(time());

$search_options = SearchOptions::get_options();

require "templates/$prefix/index.html";
$page->output();

?>
