<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "db.php";

class ShuttleSchedule {
   
  //store an array of all the shuttle routes
  private $routes;
  /****************************************************
   * this method adds a shuttle route to the schedule
   * and returns the added route     
   ****************************************************/
  public function route($routeName, $shortName, $isSafeRide = False) {
    $route = new Route($routeName, $shortName, $isSafeRide);
    $this->routes[] = $route;
    return $route;
  }

  public function getRoutes() {
    return $this->routes;
  }

  public function getRoute($name) {
    $encodedName = Route::encode($name);
    foreach($this->routes as $route) {
      if($route->encodeName() == $encodedName) {
        return $route;
      }
    }
  }

  public function initRoutesCache() {
    $last_stop_tags = array();
    foreach($this->routes as $route) {
      $last_stop_tags[$route->encodeName()] = $route->lastStopTags();
    }
    $next_bus_data = Route::queryNextBus($last_stop_tags);
    
    foreach($this->routes as $route) {
      $key = $route->encodeName();
      $route->setCache(array($key => $next_bus_data[$key]));
    }
  }  
}

class Route implements Iterator{

  // name of shuttle
  private $name;

  // shortname of shuttle used for nextBus
  private $shortName;

  // if this is a saferide route
  private $is_safe_ride;

  // number of round trips per hour
  private $perHour;
  
  // this hash stores the name of all the stops
  // and the time it reaches the stop relative to the route
  private $stops;

  // this hash store all the required next bus tags
  private $next_bus_tags;

  private $key;

  // a brief string describing when the route is active
  private $summary;

  // does this shuttle run even on holidays
  private $holidays=True;

  /*****************************************************
   * a hash mapping days of the week to the routes schedule
   * for that day
   * the route schedule contains such information as
   * this list of hours for each bus
   * that operates on this route
   * how many times it operates that hour
   * and a number a minute delay relative to the 
   * standard route times
   *****************************************************/
  private $days;

  // XML NextBus Data Cache
  private $cached = False;
  private $next_bus_cache;

  public function __construct($name, $shortName, $isSafeRide=False) {
    $this->name = $name;
    $this->shortName = $shortName;
    $this->is_safe_ride = (bool) $isSafeRide;
  }
  
  public function except_holidays() {
    $this->holidays = False;
    return $this;
  }

  public function getHolidays() {
    return $this->holidays;
  }

  public function summary($summary) {
    $this->summary = $summary;
    return $this;
  }

  public function getSummary() {
    return $this->summary;
  }

  public function isSafeRide() {
    return $this->is_safe_ride;
  }

  public static function encode($name) {
    $noSpaces = str_replace(' ', '_', $name);
    return strtolower($noSpaces);
  }

  public function encodeName() {
    return self::encode($this->name);
  }

  public function getStopIndex($name) {
    return array_search($name, array_keys($this->stops));;
  }

  public function isDayEmpty(day $day) {
    return !array_key_exists($day->abbrev(), $this->days);
  }

  public function firstStop(day $day) {    
    if($this->isDayEmpty($day)) {
      return NULL;
    }

    return $this->getByKey($this->first_key($day));
  }

  public function perHour($perHour) {
    $this->perHour = $perHour;
    return $this;
  }

  public function getPerHour() {
    return $this->perHour;
  }

  public function stops() {
    $stops_data = func_get_args();
    $this->stops = array();
    $this->next_bus_tags = array();
    foreach($stops_data as $stop) {
      $this->stops[ $stop['name'] ] = $stop['time'];
      if($stop['shortName']) {
        $this->next_bus_tags[ $stop['name'] ] = array(
          'shortName' => $stop['shortName'], 
          'direction' => $stop['direction']
        );
      }
    } 
    return $this;
  }

  public function addHours() {
    $tmp = func_get_args();
    $days_str = array_shift($tmp);
    $days = day::factory($days_str);
    foreach($days as $day) {
      $this->days[$day->abbrev()] = $tmp;
    }
    return $this;
  }

  public function getName() {
    return $this->name;
  }
 
  public function populate_db() {
    $db = db::$connection;
    $stmt = $db->prepare(
      "INSERT INTO Schedule (day_scheduled, day_real, route, place, hour, minute) values (?, ?, ?, ?, ?, ?)"
    );

    foreach(day::$days as $day) {
      $this->day = new day($day);
      foreach($this as $stop) {
        $stmt->bind_param('ssssii', $day, $stop->getDay(), $this->encodeName(), $stop->getName(), $stop->getHour(), $stop->getMinute());
        $stmt->execute();
      }
    }
  }

  public function getNextStop($day, $hour, $minute) {
    /**********************************************
     * Warning this code assumes that only daytime shuttles
     * do not run on holidays
     **********************************************/

    $day = new day($day);
    $db = db::$connection;

    // find the first stop after the given time
    $stmt_1 = $db->prepare(
      "SELECT day_scheduled, place, hour, minute FROM Schedule WHERE day_real = ? AND route = ? AND (60 * hour + minute) >= ? ORDER BY (60 * hour + minute) LIMIT 1" );
 
    $day_offset = 0;
    $day_scheduled = NULL;
    $first_stop = NULL;
    $total_minutes = 60 * $hour + $minute;

    // loop through successive days until we find the first stop
    while(!$first_stop){
      if($this->holidays || !Holidays::is_holiday($day_offset)) {
        $stmt_1->bind_param('ssi', $day->abbrev(), $this->encodeName(), $total_minutes);
        $stmt_1->bind_result($day_scheduled, $first_stop, $hour, $minute);
        $stmt_1->execute();
      }

      if(!$stmt_1->fetch()) {
        $day_offset++;
        $day = $day->next();
        $total_minutes = 0;
      }         
    } 
    $stmt_1->free_result();
    $stmt_1->close();

    return array(
      "real_day"      => $day, 
      "scheduled_day" => $day_scheduled, 
      "next_stop"     => $first_stop, 
      "hour"          => $hour, 
      "minute"        => $minute,
      "total_minutes" => $total_minutes
    );
  }

  public function isRunning($day, $hour, $minute) {
    if($this->GPSisActive()) {         
      return True;
    } else {
      return $this->isRunningFromDB($day, $hour, $minute);
    }
  }
  
  public function isRunningFromDB($day, $hour, $minute) {
    if(!$this->holidays && Holidays::is_holiday()) {
      // this shuttle does not run on holidays
      // and today is a holiday
      return False;
    }
    
    $next_stop = $this->getNextStop($day, $hour, $minute);
    $day = new day($day);    

    // Calculate a time offset if the next stop is tommorow
    if($day == $next_stop["real_day"]) {
      $offset = 0;
    } elseif($day->next() == $next_stop["real_day"]) {
      $offset = 24 * 60;
    } else {
      // the next stop is not anytime soon, more than a day into
      // future
      return False;
    }
    
    $next_stop_time = $offset + $next_stop["hour"] * 60  + $next_stop["minute"];
    $this_time = $hour * 60 + $minute;

    if($next_stop_time - $this_time > 25) {
      // next stop more than 25 minutes into the future
      return False;
    } else {
      return True;
    }
  }

  public function getCurrentStops($day, $hour, $minute) {

    if($this->isRunning($day, $hour, $minute) && $this->GPSisActive()) {
      // query next bus to get the upcoming times
      $times = $this->getNextBusTimes();      
      $places = array_keys($times);

      foreach($this->stops as $place => $dummy) {
        if(array_key_exists($place, $this->next_bus_tags)) {

          // adds an extra second for safe measure
          $seconds = $times[$place] + 1;
          $index = array_search($place, $places);
          $previous = !$index ? count($times)-1 : $index-1;         

          $total = (60 * $hour + $minute) * 60 + $seconds;
          $hours = (int)($total / 60 / 60) % 24;
          $minutes = (int)($total / 60) % 60;
          $previous_time =  $times[$places[$previous]];
          $never = ($times[$place] === NULL);
          $next = (($times[$place] < $previous_time) || ($previous_time === NULL)) && !$never;
         
          $stops[] = array(
	    "next"   => $next,
	    "place"  => $place,
	    "hour"   => $never ? NULL : Stop::makeDD($hours),
	    "minute" => $never ? NULL : Stop::makeDD($minutes),
            "never"  => $never
	  );
        } else {
          // this stopped is not tracked by Next Bus
          $stops[] = array("next"=>False, "place"=>$place, "hour"=>NULL, "minute"=>NULL, "never"=>False, "unknown"=>True);
        }
      }
      return $stops;
    } else {
      // fall back to schedule stored in the database
      return $this->getCurrentStopsFromDB($day, $hour, $minute);
    }
  }

  public function GPSisActive() {
    $times = $this->getNextBusTimes();
    $keys = array_keys($times);
    $last = $keys[count($times)-1];
    return ($times[$last] !== NULL);
  }
  
  private function getNextBusTimes() {
    if($this->cached) {
      return $this->next_bus_cache;
    } else {
      $this->next_bus_cache = self::queryNextBus($this->next_bus_tags, $this->shortName);
      $this->cached = True;
      return $this->next_bus_cache;
    }
  }

  public function lastStopTags() {
    $keys = array_keys($this->next_bus_tags);
    $key = $keys[count($this->next_bus_tags) - 1];
    $tags = $this->next_bus_tags[$key];
    $tags['routeName'] = $this->shortName;
    return $tags;
  }
    
  public function setCache($data) {
    $this->cached = True;
    $this->next_bus_cache = $data;
  }

  public static function queryNextBus($stop_tags, $routeName=NULL) {
    $agency = "mit";
    $query = "command=predictionsForMultiStops&a=$agency";

    foreach($stop_tags as $tags) {
      $routeShortName = $routeName ? $routeName : $tags['routeName'];
        
      $query .= "&stops=$routeShortName|{$tags['direction']}|{$tags['shortName']}";
    }

    $xml = file_get_contents("http://www.nextbus.com/s/xmlFeed?$query");

    if($xml) {
      $xml_obj = new DOMDocument();
      $xml_obj->loadXML($xml);

      $errors = $xml_obj->getElementsByTagName('Error');
      foreach($errors as $error) {
	throw new Exception("Next Bus Server Error: $error->nodeValue");
      }

      $predictions = $xml_obj->getElementsByTagName('predictions');
      reset($stop_tags);
      $times = array();
      foreach($predictions as $stop) {
	$children = getChildrenByTagName($stop, 'direction');
	$name = key($stop_tags);
	if(count($children) == 1) {
	  $nodes = getChildrenByTagName($children[0], 'prediction');
	  $times[$name] = $nodes[0]->getAttribute('seconds');
	} else {
	  $times[$name] = NULL;
	}
	next($stop_tags);
      }
      return $times;
    }
  }

  public function getCurrentStopsFromDB($day, $hour, $minute) {
    $db = db::$connection;

    $next_stop = $this->getNextStop($day, $hour, $minute);

    $first_stop = $this->isRunning($day, $hour, $minute) ? $next_stop["next_stop"] : -1;


    $day_scheduled = $next_stop["scheduled_day"];
    $day = $next_stop["real_day"];
    $total_minutes = $next_stop["total_minutes"];

    $stmt_2 = $db->prepare(
      "SELECT day_real, hour, minute FROM Schedule WHERE day_scheduled = ? AND day_real = ? AND place = ? AND route = ? AND (60 * hour + minute) >= ? ORDER BY (60 * hour + minute) LIMIT 1" );

    $stops = array();   
    $zero = 0;
    $stmt_2->bind_result($day_real, $hour_res, $minute_res);

    // find the next time for each stop of the route
    foreach(array_keys($this->stops) as $place) {
      $stmt_2->bind_param('ssssi', 
        $day_scheduled, 
	$day->abbrev(),
	$place,
	$this->encodeName(),
        $total_minutes
      );
      $stmt_2->execute();

      if(!$stmt_2->fetch()) {
        // no remaining times were found today
        // so check if the stop has a time after midnight
        // on the next day
        
        $stmt_2->bind_param('ssssi', 
          $day_scheduled, 
	  $day->next()->abbrev(),
	  $place,
	  $this->encodeName(),
	  $zero
        );

        $stmt_2->execute();
        $stmt_2->fetch();
      }

      $stmt_2->free_result();

      $never = !(bool) $day_real;

      //store the result
      $stops[] = array(
	"next"   => ($place == $first_stop),
	"place"  => $place,
        "day"    => $never ? NULL : $day_real,
        "hour"   => $never ? NULL : Stop::makeDD($hour_res),
        "minute" => $never ? NULL : Stop::makeDD($minute_res),
        "never"  => $never 
      );
     
    }
    $stmt_2->close();

    return $stops; 
  }


  /******************************************************************
   *
   * These methods implement the Iterator pattern
   *
   *****************************************************************/
  public function rewind() {
    $this->key = $this->first_key($this->day);
  }

  public function first_key(day $day) {
    $buses = array();
    if(!$this->isDayEmpty($day)) {
      foreach($this->days[$day->abbrev()] as $bus) {
        $buses[] = array("hour" => 0, "trip" => 0, "stop" => 0);
      }
    }      
    return array("day" => $day, "buses" => $buses);
  }

  public function getByKey(array $key) {
    if($this->isDayEmpty($key["day"])) {
      return NULL;
    }

    $bus = $this->earliest($key["day"], $key["buses"]);
    $tmp = $key["buses"][$bus];
    return $this->make_stop($key["day"], $tmp["hour"], $tmp["trip"], $tmp["stop"], $bus);
  }

  private function make_stop(day $day, $hour, $trip, $stop, $bus) {
    $hour = $this->getHour($bus, $hour, $day);
    if(!$hour) {
      return NULL;
    }
    $places = array_keys($this->stops);
    $place = $places[$stop];
    $minute = $this->stops[$place] + $trip * (60 / $this->perHour) + $hour->getDelay();

    return new Stop($place, $hour, $minute, $this->getStopIndex($place), $day);
  }    

  public function current() {
    return $this->getByKey($this->key);
  }

  public function key() {
    return $this->key;
  }

  private function earliest(day $day, $buses, $skip = 0) {
    return $this->earliest_or_latest($day, $buses, True, $skip);
  }

  private function latest(day $day, $buses, $skip = 0) {
    return $this->earliest_or_latest($day, $buses, False, $skip);
  }

  private function earliest_or_latest(day $day, $buses, $earliest, $skip) {
    $stop_bus_pairs = array();
    foreach($buses as $index => $bus) {
      $stop_bus_pairs[] = array(
	$this->make_stop($day, $bus["hour"], $bus["trip"], $bus["stop"], $index),
        "bus" => $index
      );
    }
    
    $which_key = $earliest ? $skip : count($buses) - 1 - $skip;

    //sort the stops by time
    usort($stop_bus_pairs, array("Stop", "compare"));
    return $stop_bus_pairs[$which_key]["bus"];
  }    
  
  private function getHour($bus, $hour_index, day $day) {
    $hours = $this->days[$day->abbrev()][$bus]->getHours();
    return $hours[$hour_index];
  }
    

  public function next_day_key($key) {
      return $this->first_key($key["day"]->next());
  }

  public function next_key(array $key) {
    // this function works by incrementing the earliest
    // bus.

    $buses = $key["buses"];
    $bus = $this->earliest($key["day"], $buses);
    $aBus = $buses[$bus];

    if($this->isDayEmpty($key["day"])
       || !($hour = $this->getHour($bus, $aBus["hour"], $key["day"])) ) {
      return $this->first_key($key["day"]->next());
    }

    $aBus["stop"] += 1;
    if($aBus["stop"] == count($this->stops)) {
      $aBus["stop"] = 0;
      $aBus["trip"] += 1;
      if($aBus["trip"] == $hour->getTrips($this->perHour)) {
        $aBus["trip"] = 0;
        $aBus["hour"] += 1;
      }     
    }

    $key["buses"][$bus] = $aBus;
    return $key;
  }

  public function next() {
    $this->key = $this->next_key($this->key);

    if($this->current()) {
      return $this->current();
    } else {
      return False;
    }
  } 

  public function valid() {
    if($this->isDayEmpty($this->key["day"])) {
      return False;
    }
    return ($this->current() !== NULL);
  }
}

class Stop {
  private $hour;
  private $minute;
  private $name;
  private $past_midnight;
  private $day;
  private $stop_index;

  public function __construct($name, Hour $hour, $minute, $stop, day $day) {
    $minute = (int) $minute;
    $this->hour = (int) $hour->getHour();
    $this->minute = $minute % 60; 

    // if more than 60 minutes will need to increment the hour
    $this->hour = $this->hour + (int) ($minute / 60);
    $this->past_midnight = $hour->isPastMidnight();
    if($this->hour >= 24) {
      $this->hour -= 24;
      $this->past_midnight = True;
    }
    $this->stop_index = $stop;
    $this->name = $name;
    $this->day = $this->past_midnight ? $day->next() : $day;
  }

  public function getName() {
    return $this->name;
  }
  
  public function getIndex() {
    return $this->stop_index;
  }
  
  public function getAMPM() {
    return ($this->hour < 12) ? 'A' : 'P';
  }
  
  public function getStandardHour() {
    $tmp = $this->hour % 12;
    return ($tmp !== 0) ? $tmp : 12;
  }

  public function getHour($standard_form=True) {
    $wholeday = (!$standard_form && $this->past_midnight) ? 24 : 0;

    return $this->hour + $wholeday;
  }

  public function getTime() {
    return $this->getStandardHour() . ':' . $this->getMinute() . $this->getAMPM() . 'M';
  }


  public static function makeMilitaryTime($hour, $minute) {
    $hour = self::makeDD($hour);
    return $hour . ':' . self::makeDD($minute);
  }

  public function getMilitaryTime() {
    return self::makeMilitaryTime($this->hour, $this->minute);
  }

  public function getMinute() {
    return self::makeDD($this->minute);
  }

  public function makeDD($num) {
    return ($num < 10) ? ('0' . $num) : $num;
  }

  public function getDay() {
    return $this->day->abbrev();
  }

  public function isBefore($day, $hour, $minute=0) {

    switch (day::compare($this->day, $day)) {
      case 0:
        if($hour == $this->hour) {
          return ($this->minute < $minute);
        } else {
          return ($this->hour < $hour);
        }
      case -1:
        return True;
      case +1:
        return False;
    }
  }

  public static function compare($stop1, $stop2) {
    //first array element is the stop
    //the "bus" element is the bus number
    $stop1 = $stop1[0];
    $stop2 = $stop2[0];

    //NULL stop are at the end of time.
    if(!$stop1) {
      return 1;
    }
    
    if(!$stop2) {
      return -1;
    }

    if($stop1->day != $stop2->day) {
      return day::compare($stop1->day, $stop2->day);
    }

    if($stop1->hour != $stop2->hour) {
      return $stop1->hour > $stop2->hour ? +1 : -1;
    }

    if($stop1->minute != $stop2->minute) {
      return $stop1->minute > $stop2->minute ? +1 : -1;
    }
 
    return 0;
  }
}
  
class Hour {
  private $default_trip_number = True;
  private $trip_number;
  private $hour;
  private $delay=0;
  private $past_midnight = False;

  public function pastMidnight() {
    $this->past_midnight = True;
  }

  public function isPastMidnight() {
    return $this->past_midnight;
  }

  public function getHour() {
    return $this->hour;
  }

  public function getDelay() {
    return $this->delay;
  }

  public function setDelay($delay) {
    return $this->delay = $delay;
  }

  public function getTrips($perHour) {
    if($this->default_trip_number) {
      return $perHour;
    } else {
      return $this->trip_number;
    }
  }

  public function __construct($hour, $trip_number = NULL) {
    $this->hour = $hour;
    if($trip_number !== NULL) {
      $this->trip_number = $trip_number;
      $this->default_trip_number = False;
    }
  }
} 
 
class HourList {
    
  private $past_midnight = False;
  private $hours = array();

  public function append(HourList $new_hours) {
    foreach($new_hours->hours as $hour) {
      $this->add_single($hour);
    }
    return $this;
  }

  public function add_single(Hour $hour) {
    $hours = count($this->hours);
    if($hours && $hour->getHour() < $this->hours[$hours-1]->getHour()) {
      $this->past_midnight = True;
    }

    if($this->past_midnight) {
      $hour->pastMidnight();
    }

    $this->hours[] = $hour;
  }
  
  public function delay($delay) {
    foreach($this->hours as $hour) {
      $hour->setDelay($delay);
    }
    return $this;
  }

  public function getHours() {
    return $this->hours;
  }

  public static function factory($hours_string) {
    $tmp = new self();
    $hour_pieces = explode(' ', $hours_string);
    foreach($hour_pieces as $piece) {
      $tmp->append(self::hours_helper($piece));
    }
    return $tmp;
  }

  private static function hours_helper($piece) {
    $out = new self();
    if(strpos($piece, '-') !== False) {
      $limits = explode('-', $piece);
      $lower = $limits[0];
      $upper = $limits[1];
      foreach(range($lower, $upper) as $hour) { 
        $out->add_single(new Hour($hour));
      }
    } elseif(strpos($piece, ':') !== False) {
      $nums = explode(':', $piece);
      $out->add_single(new Hour($nums[0], $nums[1]));
    } else {
      $out->add_single(new Hour($piece));
    }
    return $out;
  }
}  

class day {
  public static $days = array(
    'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
  );
  
  private static $today;
  private $day_str;
  private $day_num;

  public function setToday($day_str) {
    self::$today = new self($day_str);
  }

  public function today() {
    return self::$today;
  }

  public function plus($offset) {
    $day_num = ($this->day_num + $offset) % 7;
    return new self(self::$days[$day_num]);
  }

  public static function index($day_str) {
    return array_search($day_str, self::$days);
  }

  public function __construct($daystr) {
    $this->day_num = array_search($daystr, self::$days);
    if($this->day_num === False) {
      throw new Exception("'$daystr' is not valid day abbreviation");
    }
    $this->day_str = $daystr;
  }
  
  public function abbrev() {
    return $this->day_str;
  }
  
  public function next() {
    $day_str = self::$days[ ($this->day_num+1) % 7 ];
    return new day($day_str);
  }

  public function prev() {
    $day_str = self::$days[ ($this->day_num+7-1) % 7 ];
    return new day($day_str);
  }

  public static function factory($days_str) {
    $limits = explode('-', $days_str);
    $days = array();
    $init = $limits[0];
    $final = $limits[1];
    $current = new day($init);

    $days[] = $current;
    
    while($current->abbrev() != $final) {
      $current = $current->next(); 
      $days[] = $current;
    }
    return $days;
  }

  public static function compare($day1, $day2) {
    if($day1 == $day2) {
      return 0;
    }

    $diff =  ($day2->day_num - $day1->day_num + 7) % 7;
    if($diff < 4) {
      //day 2 is after day 1
      return -1;
    } else {
      //day 2 is before day 1
      return 1;
    } 
  }
}  
       
function hours($str) {
  return HourList::factory($str);
}

function delay($delay, $str) {
  return HourList::factory($str)->delay($delay);
}

function st($name, $shortName, $direction, $time) {
  return array(
    'name'      => $name,
    'shortName' => $shortName,
    'direction' => $direction,
    'time'      => $time
  );
}


class Holidays {
  private static $holidays;
  private static $time;

  public function init() {
    require "holiday_data.php";
    self::$holidays = array();
    foreach($holiday_data as $year => $holidays) {
      $year_array = array();
      for($cnt = 0; $cnt < count($holidays); $cnt += 2) {
        $year_array[ $holidays[$cnt] ] = $holidays[$cnt+1];
      }
      self::$holidays[$year] = $year_array;
    }
    self::$time = time();
  }

  public function is_holiday($offset=0) {
    $time = self::$time + $offset * 24 * 60 * 60;

    $year = date('Y', $time);
    $month = date('M', $time);
    $day = date('d', $time);
    foreach(self::$holidays[$year] as $date => $name) {
      $time = strtotime($date);
      if($month == date('M', $time) && $day == date('d', $time)) {
        return True;
      }
    }
    return False;
  }
    
}
Holidays::init();

function getChildrenByTagName($dom, $name) {
  $nodes = array();
  foreach($dom->childNodes as $aNode) {
    if($aNode->nodeName == $name) {
      $nodes[] = $aNode;
    }
  }
  return $nodes;
}


?>