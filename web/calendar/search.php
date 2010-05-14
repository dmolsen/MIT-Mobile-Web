<?
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

// sets up google calendar classes
require_once "lib/google_calendar.init.php";

// libs
require_once "lib/calendar.lib.php";
require_once "lib/textformat.lib.php";

$search_terms = $_REQUEST['filter'];

$search_options = SearchOptions::get_options();

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

require "templates/$prefix/search.html";
$page->output();

?>
