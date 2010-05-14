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

// libs
require_once "lib/google_calendar.init.php";
require_once "lib/calendar.lib.php";
require_once "lib/textformat.lib.php";

$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar

// the method of building a query based on newEventQuery() just didn't seem to want to work. this did though.
$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
$gdataCal = new Zend_Gdata_Calendar($client);

$error = false;
try {
	$url = 'http://www.google.com/calendar/feeds/'.$calendars[$_REQUEST['cal']]['user'].'/private/full/_'.$_REQUEST['id'];
	$event = $gdataCal->getCalendarEventEntry($url);
} catch (Exception $e) {
    $error = true;
}

if ($error == true) {
	// need to create an error handler class
	echo("<div class='error'>Error for details".$url."</div>".$e);
	exit;
} else {
	$when = $event->getWhen();
	$startTime = $when[0]->startTime;
	$endTime = $when[0]->endTime;
	$date_str = strftime('%A, %B %e, %Y',strtotime($startTime));
	$date_str_for_storage = strftime('%D',strtotime($startTime));
	$date_str_for_compare = strftime('%Y%m%d',strtotime($startTime));
	if (!(strlen($startTime) == 10)) {
	  $time_of_day = strftime('%l:%M%P',strtotime($startTime));
	  if ($endTime != '') {
	    $time_of_day .= "-".strftime('%l:%M%P',strtotime($endTime));
	  }
	}

	$description = $event->getContent()->text;

	list($description,$event_link) = getExtraData($description,'link',true);
	list($description,$contact_phone) = getExtraData($description,'contact_phone',false);
	list($description,$contact_email) = getExtraData($description,'contact_email',false);
	list($description,$contact_name) = getExtraData($description,'contact_name',false);
}

require "templates/$prefix/detail.html";
$page->output();

?>
