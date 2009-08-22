<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../page_builder/page_header.php";
require_once "../../config.gen.inc.php";
require_once "data/teams.data.inc.php";

require_once "../calendar/data/data.inc.php";
require "../calendar/lib/gcalendar_setup.php";
require "../calendar/lib/calendar_lib.php";

$links = array(
	array("teams.php","Teams",false),
	array("../map/?category=athletics","Athletic Facilities",false),
	array("http://mobile.msnsportsnet.com/","MSNsportsNET.com Mobile News",true),
	#array("http://m.twitter.com/wvusportsbuzz/","WVUSportsBuzz on Twitter",true),
	#array("http://m.twitter.com/coachstewart","Coach Stewart on Twitter",true),
	array("https://www.ticketreturn.com/wvu/","Student Tickets",true),
	array("http://mobile.msnsportsnet.com/page.cfm?section=6581", "Football Gameday FAQ (long)", true),
	array("http://mobile.msnsportsnet.com/page.cfm?section=6582", "Basketball Gameday FAQ (long)", true)
	);

$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar
$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
$gdataCal = new Zend_Gdata_Calendar($client);
$query = $gdataCal->newEventQuery();
$query->setUser($calendars['athletics']['user']);
$query->setVisibility('private');
$query->setProjection('full');
$query->setOrderby('starttime');
$query->setSortorder('a');
$query->setmaxresults('5');
$eventFeed = $gdataCal->getCalendarEventFeed($query);

require "$prefix/index.html";

$page->cache();
$page->output();

function teamURL($team) {
  return "teams.php?team=$team";
}
  
?>
