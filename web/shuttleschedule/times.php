<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../../../lib/trunk/ShuttleSchedule.php";
require "../page_builder/page_header.php";
require "schedule_lib.php";

$schedule = new ShuttleSchedule();

//include all the shuttle schedule data
require "../../../lib/trunk/shuttle_schedule.php";

$route = $schedule->getRoute($_REQUEST['route']);

$now = time();
$day = date('D', $now);
$hour = date('H', $now);
$minute = date('i', $now);
$seconds = date('s', $now);
$stops = $route->getCurrentStops($day, $hour, $minute);
$routeName = $route->getName();

function selfURL() {
  return "times.php?route={$_REQUEST['route']}";
}

function timeSTR($stop) {
  if(isset($stop['unknown'])) {
    return '';
  }
  
  if($stop['never']) {
    return 'Finished';
  }

  return standardTimeStr($stop['hour'], $stop['minute']);
}

function standardTimeStr($hour, $minute, $second = NULL) {
  $suffix_array = (Page::$phoneType == "ip") ? array('AM', 'PM') : array('A', 'P');
  $suffix = $suffix_array[ floor($hour / 12) ];
  $hour = $hour % 12;
  if($hour == 0) {
    $hour = 12;
  }
  $second_str = ($second !== NULL) ? ":$second" : "";
  return mark_up($hour . ':' . $minute . $second_str . $suffix);
}

function mark_up($string_in) {
  if(Page::$phoneType == "ip") {

    //this function adds to html markup to strings that contain times
    // 9:05AM -> 9:05<span class="ampm">AM</span>
    return preg_replace('/(\d)(AM|PM)/','$1<span class="ampm">$2</span>', $string_in);
  } else {
    return $string_in;
  }
}

function lower_first($string_in) {
  // make the first letter of a string lower case
  return strtolower(substr($string_in, 0, 1)) . substr($string_in, 1);
}

$loop_time = 60 / $route->getPerHour();

$sizes = array("sp" => 200, "fp" => 160);
$size = $sizes[$phone];

$imageURL = imageURL($phone, $route->encodeName(), $stops);

require "$prefix/times.html";
$page->output();


function imageURL($phone, $encodedName, $stops) {
  $base = "images/$phone/" . $encodedName;
  if(($index = getCurrentStop($stops)) !== NULL) {
    $base .= '-' . strtolower(Letter($index));
  }
  return $base . ".gif";
}    


function getCurrentStop($stops) {
  foreach($stops as $index => $stop) {
    if($stop['next']) {
      return $index;
    }
  }
}

?>
