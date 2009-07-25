<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";
#require "../../lib/mit_calendar.php";

// set-up Zend gData
$path = '/apache/htdocs/MIT-Mobile-Web/lib/ZendGdata-1.8.4PL1/library';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');

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

$user = 'user@gmail.com';
$pass = 'pass';
$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar

$client = Zend_Gdata_ClientLogin::getHttpClient($user,$pass,$service);
$gdataCal = new Zend_Gdata_Calendar($client);
$calFeed = $gdataCal->getCalendarListFeed();

require "$prefix/day.html";
$page->output();

?>
