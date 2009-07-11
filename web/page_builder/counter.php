<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../../lib/db.php";
require_once "Page.php";

class PageViews {
  private static $today;
  public static $time;

  private static $fields = array(
    'home', 'people', 'map', 'shuttleschedule', 'calendar',
    'stellar', 'careers', 'emergency', '3down',
    'links', 'mobile_about',
  );

  private static $devices = array(
    'ip', 'sp', 'fp', 'computer',
  );

  public static function increment($content) {
    if(Page::is_spider()) {
      // do not counter spiders as page views
      return;
    }

    $db = db::$connection;
    $content = self::url2db($content);
    if(Page::is_computer()) {
      $device = 'computer';
    } else {
      $device = Page::$phoneType;     
    }

    $db->query("LOCK TABLE PageViews WRITE");

    $today = self::$today;
    $row = self::getDay($today);

    if($row === NULL) {
      $content_cnt = 1;
      $device_cnt = 1;
      $db->query("INSERT INTO PageViews (day) VALUES ('$today')");
    }
    $current_cnt = $row[$content] + 1;
    $device_cnt = $row[$device] + 1;

    $db->query("UPDATE PageViews SET $content={$current_cnt}, $device={$device_cnt} WHERE day='$today'");
    $db->query("UNLOCK TABLE");
  }

  public static function init() {
    self::$time = time();
    self::$today = date("Y-m-d", self::$time);
  }

  private static function getDay($day) {
    $db = db::$connection;
    $result = $db->query("SELECT * FROM PageViews", SQLITE_ASSOC, $query_error);
    if ($query_error) {
	echo("error: ".$query_error);
        exit;
    }
    if($row = $result->fetch()) {
      return $row;
    }
  }

  public static function past_days($days) {
    $time = self::$time;
    $views = array();
    for($cnt = 0; $cnt < $days; $cnt++) {
      $time -= 24 * 60 * 60;
      $sql_date = date('Y-m-d', $time);
      $day = self::getDay($sql_date);
      $name = date('D', $time);
      $date = date('n/j', $time);

      if($day === NULL) {
	//day has no data so all views are zero
        $day = array('day' => $sql_date);
        foreach(self::$fields as $field) {
          $day[$field] = 0;
        }
        foreach(self::$devices as $device) {
          $day[$device] = 0;
        }
      }
      $day['name'] = $name;
      $day['date'] = $date;
      $day['total'] = 0;
  
      //find the total for each day
      foreach(self::$devices as $device) {
        $day['total'] += $day[$device];
      }

      $views[] = $day;
    }
    return array_reverse($views);
  }

  public static function url2db($name) {
   return str_replace('-', '_', $name);
  }

  private static function db2url($name) {
   return str_replace('_', '-', $name);
  }

}

PageViews::init();

?>
