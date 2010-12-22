<?php

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

// defines all the variables related to being today
require_once "lib/calendar.lib.php";
require_once "lib/textformat.lib.php";

$time = $_REQUEST['time'];
$current = day_info($time);
$next = day_info($time, 1);
$prev = day_info($time, -1);

$eventFeed = CalendarAdapter::getDayEvents($current['gdate'], $next['gdate']);

// this is the switch for requests from the main page for the number of events for today
if ($_REQUEST['countonly'] == true) {
	if ($error == false) {
		$i = 0;
		foreach ($eventFeed as $event) { $i++; }
		echo($i);
	} else {
		echo(0);
	}
}
else {
	require "templates/$prefix/day.html";
	$page->output();
}

?>
