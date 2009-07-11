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

//defines all the variables related to being today
require "calendar_lib.php";

$time = $_REQUEST['time'];
$current = day_info($time);
$next = day_info($time, 1);
$prev = day_info($time, -1);
$type = $_REQUEST['type'];
$Type = ucwords($type);

$methodName = "Todays{$Type}Headers";
$events = MIT_Calendar::$methodName($current['date']);

require "$prefix/day.html";
$page->output();

?>
