<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "ShuttleSchedule.php";
require_once "../config.gen.inc.php";
$schedule = new ShuttleSchedule();
require $bus_schedule;

foreach($schedule->getRoutes() as $route) {
  $route->populate_db();
}

?>
