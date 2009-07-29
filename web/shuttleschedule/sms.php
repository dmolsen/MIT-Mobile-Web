<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../../lib/ShuttleSchedule.php";
require "schedule_lib.php";
require "../../config.gen.inc.php";

if($search_terms = $_REQUEST["a"]) {
} else {
  $search_terms = "";
}

$short_shuttles = array();
$short_shuttles['blue'] = 'blue_line';
$short_shuttles['bgc'] = 'blue_and_gold_connector';

$sms_route = $short_shuttles[$_REQUEST['a']];
$route = $schedule->getRoute($sms_route);

$now = time();
$day = date('D', $now);
$hour = date('H', $now);
$minute = date('i', $now);
$seconds = date('s', $now);
$stops = $route->getCurrentStops($day, $hour, $minute);
$routeName = $route->getName();

if (!$route->isRunning($day, $hour, $minute)) { 
	echo("This shuttle, ".$routeName.", is not currently running.");
	exit;
} 

foreach ($stops as $index => $stop) { 
  if($stop['next']) {
    echo("Next stop for the ".$routeName.": ".$stop['place']." @ ".timeSTR($stop);
  }
}

?>
