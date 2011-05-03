<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// set-up Zend gData
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');

require_once($library_path . DIRECTORY_SEPARATOR . 'cache.php');

class CalendarAdapter extends ModuleAdapter {  
	
	// standardized method to set-up connection with Google Cal
	private static function setUpConnection() {
		
		# credentials for the google calendar
		include("credentials.inc.php");
		
		# connection method
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar
		$client = Zend_Gdata_ClientLogin::getHttpClient($username,$password,$service);
		$gdataCal = new Zend_Gdata_Calendar($client);
		return $gdataCal;
	}
	
	// match the feed from Google Calendar with the actual key names we use in templates
	private static function convertFeed($eventFeed) {
		
		$convertedFeed = array();
		foreach ($eventFeed as $event) {
			
			preg_match("/full\/(.*)$/i",$event->getId()->getText(),$matches);
			$id = $matches[1];
			
			$when = $event->getWhen();
			$startTime = $when[0]->startTime;
			$endTime = $when[0]->endTime;
			$date_str = strftime('%A, %B %e, %Y',strtotime($startTime));
			$date_str_for_storage = strftime('%D',strtotime($startTime));
			$date_str_for_compare = strftime('%Y%m%d',strtotime($startTime));

			// Reset the time used in templates since not all events specify a start time
			$time_of_day = '';
			if (!(strlen($startTime) == 10)) {
			  $time_of_day = strftime('%l:%M%P',trim(strtotime($startTime)));
			  if ($endTime != '') {
			    $time_of_day .= " - ".strftime('%l:%M%P',trim(strtotime($endTime)));
			  }
			}	
			
			$title = $event->getTitleValue();
			$description = $event->getContent()->getText();

			// the following getExtraData is specific to WVU's implementation with Google Calendar
			list($description,$event_link) = getExtraData($description,'link',true);
			list($description,$contact_phone) = getExtraData($description,'contact_phone',false);
			list($description,$contact_email) = getExtraData($description,'contact_email',false);
			list($description,$contact_name) = getExtraData($description,'contact_name',false);
			
			$where = $event->getWhere();
			$where = $where[0]->getValueString();
			
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
		
		# google calendar IDs
		include("googlecalids.inc.php");
							
		return $google_cal_ids[$key];
	}

	private static function loadQuery($gdataCal, $query) {
		$cache = mwosp_get_cache();

		$cacheId = md5($query->getQueryUrl());
		$convertedFeed = array();

		if (($convertedFeed = $cache->load($cacheId)) === false) {
			try {
				$eventFeed = $gdataCal->getCalendarEventFeed($query);
				$convertedFeed = self::convertFeed($eventFeed);
				$cache->save($convertedFeed, $cacheId);
			}
			catch (Exception $e) {
				error_log($e);
			}
		}

		return $convertedFeed;
	}

	private static function loadEvent($gdataCal, $url) {
		$cache = mwosp_get_cache();

		$cacheId = md5($url);
		$convertedFeed = array();

		if (($convertedFeed = $cache->load($cacheId)) === false) {
			try {
				$event = $gdataCal->getCalendarEventEntry($url);
				$convertedFeed = self::convertFeed(array($event));
				$cache->save($convertedFeed, $cacheId);
			}
                        catch (Exception $e) {
				error_log($e);
			}
		}

		return $convertedFeed;
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
		$query->setSingleEvents(true);

		$convertedFeed = self::loadQuery($gdataCal, $query);

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
		$query->setSingleEvents(true);
		
		$convertedFeed = self::loadQuery($gdataCal, $query);

		return $convertedFeed;
	}
	
	public static function getEvent($id,$calkey) {
		
		$calid = self::getGoogleCalID($calkey);
		
		$gdataCal = self::setUpConnection();
		$url = 'http://www.google.com/calendar/feeds/'.$calid.'/private/full/'.$id;

		$convertedFeed = self::loadEvent($gdataCal, $url);
		
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
		$query->setSingleEvents(true);
		$query->setQuery($search_terms);
		
		$convertedFeed = self::loadQuery($gdataCal, $query);

		return $convertedFeed;
	}
}

?>
