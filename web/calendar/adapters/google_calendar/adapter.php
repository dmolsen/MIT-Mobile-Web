<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// set-up Zend gData
$path = $install_path.'lib/ZendGdata-1.8.4PL1/library';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');

class CalendarAdapter extends ModuleAdapter {  
	
	// standardized method to set-up connection with Google Cal
	private static function setUpConnection() {
		
		# credentials for the google calendar
		$username = "wvucalendar2";
		$password = "Ars3nal#10";
		
		# connection method
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar
		$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
		$gdataCal = new Zend_Gdata_Calendar($client);
		return $gdataCal;
	}
	
	// match the feed from Google Calendar with the actual key names we use in templates
	private static function convertFeed($eventFeed) {
		
		$convertedFeed = array();
		foreach ($eventFeed as $event) {
			
			preg_match("/_(.*)$/i",$event->id->text,$matches);
		    $id = $matches[1];
			
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
			
			$title = $event->title->text;
			$description = $event->getContent()->text;

			// the following getExtraData is specific to WVU's implementation with Google Calendar
			list($description,$event_link) = getExtraData($description,'link',true);
			list($description,$contact_phone) = getExtraData($description,'contact_phone',false);
			list($description,$contact_email) = getExtraData($description,'contact_email',false);
			list($description,$contact_name) = getExtraData($description,'contact_name',false);
			
			$where = $event->getWhere();
			$where = $where->valueString;
			
			$convertedFeed[] = array('id' => $id,
									 'title' => $title,
									 'starttime' => $startTime,
									 'endtime' => $endTime,
									 'datefull' => $date_str,
									 'timefull' => $time_of_day,
									 'datestorage' => $date_str_for_storage, // for WebKit templates & local storage
									 'datecompare' => $date_str_for_compare, // for WebKit templates & local storage
									 'where' => $where,
									 'description' => $description,
									 'link' => $event_link,
									 'contactphone' => $contact_phone,
									 'contactemail' => $contact_email,
									 'contactname' => $contact_name);
		}
		
		return $convertedFeed;
	}
	
	// match the categories from data.inc.php to their respective google calendar IDs to properly pull data
	private static function getGoogleCalID($key) {
		
		// the keys for this array should match the keys in the $calendars array from data.inc.php
		// IMPORTANT: their should be an 'all' category that contains all events for search purposes
		$google_cal_ids = array("all" => "pcnqpk03212bhbvvq03hc0ouv8k0oj76@import.calendar.google.com",
							    "academic" => "7q0f77k0oi0uiet6el58htdptdi1f4r5@import.calendar.google.com");
							
		return $google_cal_ids[$key];
	}
	
	public static function getCategoryEvents($calkey) {
		
		$calid = self::getGoogleCalID($calkey);
		
		$gdataCal = self::setUpConnection();
		
		$query = $gdataCal->newEventQuery();
		$query->setUser($calid);
		$query->setVisibility('private');
		$query->setProjection('full');
		$query->setOrderby('starttime');
		$query->setSortorder('a');
		$query->setmaxresults('30');
		
		try {
			$eventFeed = $gdataCal->getCalendarEventFeed($query);
		} catch (Exception $e) {
		    return false;
		}
		
		$convertedFeed = self::convertFeed($eventFeed);
		
		return $convertedFeed;
	}
	
	public static function getDayEvents($starttime,$endtime) {
		
		$calid = self::getGoogleCalID('all');
		
		$gdataCal = self::setUpConnection();
		
		$query = $gdataCal->newEventQuery();
		$query->setUser($calid);
		$query->setVisibility('private');
		$query->setProjection('full');
		$query->setOrderby('starttime');
		$query->setSortorder('a');
		$query->setStartMin($starttime);
		$query->setStartMax($endtime);
		$query->setmaxresults('30');
		
		try {
			$eventFeed = $gdataCal->getCalendarEventFeed($query);
		} catch (Exception $e) {
		    return false;
		}
		
		$convertedFeed = self::convertFeed($eventFeed);
		
		return $convertedFeed;
	}
	
	public static function getEvent($id,$calkey) {
		
		$calid = self::getGoogleCalID($calkey);
		
		$gdataCal = self::setUpConnection();
		
		try {
			$url = 'http://www.google.com/calendar/feeds/'.$calid.'/private/full/_'.$_REQUEST['id'];
			$event = $gdataCal->getCalendarEventEntry($url);
		} catch (Exception $e) {
		    return false;
		}
		
		$convertedFeed = self::convertFeed(array($event)); // the extra array() is a hack to allow convertFeed() to work w/out changes
		
		return $convertedFeed;

	}
	
	public static function searchEvents($search_terms,$starttime,$endtime) {
		
		$calid = self::getGoogleCalID('all');
		
		$gdataCal = self::setUpConnection();
		
		$query = $gdataCal->newEventQuery();
		$query->setUser($calid);
		$query->setVisibility('private');
		$query->setProjection('full');
		$query->setOrderby('starttime');
		$query->setSortorder('a');
		$query->setStartMin($starttime);
		$query->setStartMax($endtime);
		$query->setmaxresults('50');
		$query->setQuery($search_terms);
		
		try {
			$eventFeed = $gdataCal->getCalendarEventFeed($query);
		} catch (Exception $e) {
		    return false;
		}
		
		$convertedFeed = self::convertFeed($eventFeed);
		
		return $convertedFeed;
	}
}

?>