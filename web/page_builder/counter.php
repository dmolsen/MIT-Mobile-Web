<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "MDB2.php";
require_once $install_path."lib/db.php";
require_once "Page.php";

class PageViews  {
  private static $today;
  public static $time;

  public static function increment($section) {
    
	// do not counter spiders as page views
	if (Device::is_spider()) {  
      return;
    }
    
	// set-up the database connection to be used further down
	$db = new db;
	$db->connection->setFetchMode(MDB2_FETCHMODE_ASSOC);
	
    $content = self::url2db($section);
    if(Device::is_computer()) {
      $device = 'computer';
    } else {
      $device = Device::classify();   
    }

    $today = self::$today;

	// get and save data related to today and the section the user is in
    $row_section = self::getDaySection($today,$section);
    if ($row_section === NULL) {
	  $types = array('text','text');
      $stmt =& $db->connection->prepare("INSERT INTO Pageviews_by_Section (day,section) VALUES (?,?)",$types);
      $stmt->execute(array($today,$section));
    }

    $section_count = $row_section['count'] + 1;

	$types = array('integer','text','text');
    $stmt =& $db->connection->prepare("UPDATE Pageviews_by_Section SET count = ? WHERE day = ? and section = ?",$types);
    $stmt->execute(array($section_count,$today,$section));

	// get and save data related to today and the device the user is using
    $row_device = self::getDayDevice($today,$device);
    if ($row_device === NULL) {
	  $types = array('text','text');
      $stmt =& $db->connection->prepare("INSERT INTO Pageviews_by_Device (day,device) VALUES (?,?)",$types);
      $stmt->execute(array($today,$device));
    }

    $device_count = $row_device['count'] + 1;

	$types = array('integer','text','text');
    $stmt =& $db->connection->prepare("UPDATE Pageviews_by_Device SET count = ? WHERE day = ? and device = ?",$types);
    $stmt->execute(array($device_count,$today,$device));
  }

  public static function init() {
    self::$time = time();
    self::$today = date("Y-m-d", self::$time);
  }

  private static function getDaySection($day,$section) {
	$db = new db;
	$db->connection->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$types = array('text');
    $stmt =& $db->connection->query("SELECT * FROM Pageviews_by_Section WHERE day='$day' and section = '$section'");
    if($row = $stmt->fetchRow()) {
        return $row;
    }
  }

  private static function getDayDevice($day,$device) {
	$db = new db;
	$db->connection->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$types = array('text');
    $stmt =& $db->connection->query("SELECT * FROM Pageviews_by_Device WHERE day='$day' and device = '$device'");
    if($row = $stmt->fetchRow()) {
        return $row;
    }
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
