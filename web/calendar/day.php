<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";

// various copy includes
require_once "../../config.gen.inc.php";

// sets up google calendar classes
require "gcalendar_setup.php";

// defines all the variables related to being today
require "calendar_lib.php";

$time = $_REQUEST['time'];
$current = day_info($time);
$next = day_info($time, 1);
$prev = day_info($time, -1);
$type = $_REQUEST['type'];
$Type = ucwords($type);

#$methodName = "Todays{$Type}Headers";
#$events = MIT_Calendar::$methodName($current['date']);

$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar

$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
$gdataCal = new Zend_Gdata_Calendar($client);
$query = $gdataCal->newEventQuery();
$query->setUser('e5c02e64sdtcq4vqtt8441ib50fovr3f%40import.calendar.google.com');
$query->setVisibility('public');
$query->setProjection('basic');
$query->setOrderby('starttime');
$query->setFutureevents('true');
$eventFeed = $gdataCal->getCalendarEventFeed($query);

require "$prefix/day.html";
$page->output();

?>
