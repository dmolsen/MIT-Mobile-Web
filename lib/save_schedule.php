<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "ShuttleSchedule.php";
$schedule = new ShuttleSchedule();
require "shuttle_schedule.php";

foreach($schedule->getRoutes() as $route) {
  $route->populate_db();
}

?>
