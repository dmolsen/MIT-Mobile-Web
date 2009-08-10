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
require_once "data/data.inc.php";

// sets up google calendar classes
require "lib/gcalendar_setup.php";

// defines all the variables related to being today
require "lib/calendar_lib.php";

$id = $_REQUEST['id'];

$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar
$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
$gdataCal = new Zend_Gdata_Calendar($client);
$query = $gdataCal->newEventQuery();
$query->setUser($calendars[$id]['user']);
$query->setVisibility('private');
$query->setProjection('full');
$query->setOrderby('starttime');
$query->setSortorder('a');
$query->setmaxresults('30');
$eventFeed = $gdataCal->getCalendarEventFeed($query);

require "$prefix/category.html";
$page->output();

?>
