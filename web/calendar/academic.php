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

$month = $_REQUEST['month'];
$year = $_REQUEST['year'];
$time = time();
if(!$month) {
  $month = strtoupper(date('F', $time));
}
if(!$year) {
  $year = date('Y', $time);
}

$prev = prev_month($month, $year);
$next = next_month($month, $year);
$prev_yr = $prev['year'];
$next_yr = $next['year'];
$prev_month = Month($prev['month']);
$next_month = Month($next['month']);
$Month = Month($month); 
$prev_url = "academic.php?year={$prev_yr}&month={$prev['month']}";
$next_url = "academic.php?year={$next_yr}&month={$next['month']}";
$days = $academic->years[$year][$month];

$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar

$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
$gdataCal = new Zend_Gdata_Calendar($client);
$query = $gdataCal->newEventQuery();
$query->setUser($calendars['academic']['user']);
$query->setVisibility('private');
$query->setProjection('full');
$query->setOrderby('starttime');
$query->setSortorder('a');
$query->setStartMin(date("Y-m-d"),mktime(0,0,0,array_search($month, month_data::$months)+1,1,$year));
$query->setStartMax(date("Y-m-d"),mktime(0,0,0,array_search($month, month_data::$months)+2,-1,$year));
$query->setmaxresults('30');
$eventFeed = $gdataCal->getCalendarEventFeed($query);

require "$prefix/academic.html";
$page->output();

class month_data {
  public static $months = array(
    "JANUARY",  "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", 
    "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"
  );
}

function next_month($month, $year) {
  $number = array_search($month, month_data::$months);
  $number++; 
  if($number == 12) {
    $year++;
    $number = 0;
  }
  return array("year" => $year, "month" => month_data::$months[$number]);
}

function prev_month($month, $year) {
  $number = array_search($month, month_data::$months);
  $number--; 
  if($number == -1) {
    $year--;
    $number = 11;
  }
  return array("year" => $year, "month" => month_data::$months[$number]);
}

?>