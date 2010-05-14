<?
/**
 * Copyright (c) 2010 West Virginia University
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

// limits the favorites to show only items from today and forward
//   since google cal deletes old events
$today = day_info(time());

// iphone only for now, just displays the interface to be
//   populated by the browser itself with data on the client
require "templates/$prefix/favorites.html";

?>