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

$search_terms = $_REQUEST['filter'];

$timeframe = isset($_REQUEST['timeframe']) ? $_REQUEST['timeframe'] : 0;
$dates = SearchOptions::search_dates($timeframe);

$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar

$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
$gdataCal = new Zend_Gdata_Calendar($client);
$query = $gdataCal->newEventQuery();
$query->setUser($calendars['all']['user']);
$query->setVisibility('private');
$query->setProjection('full');
$query->setOrderby('starttime');
$query->setSortorder('a');
$query->setStartMin($dates['start']);
$query->setStartMax($dates['end']);
$query->setmaxresults('50');
$query->setQuery($search_terms);
$eventFeed = $gdataCal->getCalendarEventFeed($query);

$form = new CalendarForm($prefix, SearchOptions::get_options($timeframe));
#$content->set_form($form);

require "$prefix/search.html";
$page->output();

?>
