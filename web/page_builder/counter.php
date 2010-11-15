<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once $install_path."lib/MDB2-2.4.1/MDB2.php";
require_once $install_path."lib/db.php";
require_once "Page.php";

class PageViews  {
  private static $today;
  public static $time;

  private static $fields = array(
    'home', 'people', 'map', 'shuttleschedule', 'calendar', 'emergency', 'links', 'youtube', 'news', 'sms', 'mobile_about', 'libraries', 'weather'
  );

  private static $devices = array(
    'webkit', 'touch', 'basic', 'computer',
  );

  public static function increment($content) {
    if(Page::is_spider()) {
      // do not counter spiders as page views
      return;
    }

    
	$db = new db;

    $content = self::url2db($content);
    if(Page::is_computer()) {
      $device = 'computer';
    } else {
      $device = Page::$phoneType;     
    }

    $today = self::$today;
    $row = self::getDay($today);

    if($row === NULL) {
      $content_cnt = 1;
      $device_cnt = 1;
	  $types = array('text');
      $stmt1 = $db->connection->prepare("INSERT INTO PageViews (day) VALUES (?)",$types);
      $stmt1->execute(array($today));
    }
    $current_cnt = $row[$content] + 1;
    $device_cnt = $row[$device] + 1;

	$types = array('integer','integer','text');
    $stmt2 = $db->connection->prepare("UPDATE PageViews SET ".$content."=CAST(? AS INT), ".$device."=CAST(? AS INT) WHERE day=?",$types);
    $stmt2->execute(array($current_cnt,$device_cnt,$today));
  }

  public static function init() {
    self::$time = time();
    self::$today = date("Y-m-d", self::$time);
  }

  private static function getDay($day) {
	$db = new db();
	$types = array('text');
    $stmt3 = $db->connection->prepare("SELECT * FROM PageViews WHERE day=?",$types);
    $stmt3->execute(array($day));
    if($row = $stmt3->fetch()) {
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

 public static function getToday() {
    // views for today
    $sql_today = date('Y-m-d');
    $today = self::getDay($sql_today);

    if($today === NULL) {
      //day has no data so all views are zero
      $today_total = 0;
    }
    else {
      foreach(self::$devices as $device) {
        $today_total += $today[$device];
      }
    }

    return $today_total;
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
