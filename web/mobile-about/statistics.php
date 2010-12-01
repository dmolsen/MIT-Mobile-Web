<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// include YAML loader
require($install_path."lib/spyc-0.4.5/spyc.php");

// force basic templates & methods for all requests
$page = Page::factory('basic');

// shared functions
function f($count) {
  $count_str = (string) $count;
  if(strlen($count_str) <= 3 ) {
    return $count_str;
  } else {
    return f(substr($count_str, 0, -3)) . ',' . substr($count_str, -3);
  }
}

function per_cent($part, $total) {
  return round(100 * $part / $total);
}

// set-up search dates
$start_time = time()-(7*24*60*60);
$end_time = time()-(24*60*60);

$end_date_sql = date('Y-m-d', $end_time);
$start_date_sql = date('Y-m-d', $start_time);
$today_sql = date('Y-m-d');

$start_month = date('M', $start_time);
$start_day = date('j', $start_time);

$end_month = date('M', $end_time);
$end_day = date('j', $end_time);

$year = date('Y', $end_time);

if ($start_month == $end_month) {
  $days_text = "{$start_month} {$start_day}-{$end_day}, $year"; 
} else {
  $days_text = "{$start_month} {$start_day}-{$end_month} {$end_day}, $year"; 
}

// set-up db
$db = new db;
$db->connection->setFetchMode(MDB2_FETCHMODE_ASSOC);

// find the largest number of views in the days
$max_views = 0;
$types = array('text','text'); // this happens to work for all the SQL calls on this page
$stmt =& $db->connection->prepare("SELECT SUM(count) AS total, day FROM Pageviews_by_Section WHERE day <= ? and day >= ? GROUP BY day LIMIT 1",$types);
$result = $stmt->execute(array($end_date_sql,$start_date_sql));
$data = $result->fetchRow();
$max_views = $data['total'];

// determine the maximum to use for the bar graph
$limits = array(1, 2, 4, 5);

$found = false;
$scale = 10;
while (!$found) {
  foreach ($limits as $limit) {
    if($limit * $scale > $max_views) {
      $max_scale = $limit * $scale;
      $found = True;
      break;
    }
  }
  $scale *= 10;
}  

// views by day
$views = array();

# grab all data by section between two dates
$stmt =& $db->connection->prepare("SELECT SUM(count) AS total, day FROM Pageviews_by_Section WHERE day <= ? and day >= ? GROUP BY day ORDER BY day DESC",$types);
$result = $stmt->execute(array($end_date_sql,$start_date_sql));
$data = $result->fetchAll();

$i = count($data);
foreach ($data as $day) {
  $day_name = date('D. m/d',time()-($i*24*60*60));
  $views[] = array(
    'day' => $day_name,
    'count' => $day['total'],
    'percent' => per_cent($day['total'], $max_scale),
  );
  $i = $i - 1;
}

// views by device
$traffic = array();

# grab all data by device between two dates
$stmt =& $db->connection->prepare("SELECT SUM(count) AS total, device FROM Pageviews_by_Device WHERE day <= ? and day >= ? GROUP BY device ORDER BY total DESC",$types);
$result = $stmt->execute(array($end_date_sql,$start_date_sql));
$all_data = $result->fetchAll();

foreach ($all_data as $data) {
  if (!($device = Device::$deviceEnglish[$data["device"]])) {
	$device = $data["device"];
  }
  $traffic[$device] = $data["total"];
}

$total = 0;
foreach ($traffic as $count) {
  $total += $count;
}
$phone_traffic = array();
foreach ($traffic as $device => $count) {
  $phone_traffic[$device] = per_cent($count, $total);
}

// views by individual sections
$popular_pages = array();

# grab all data by section between two dates
$stmt =& $db->connection->prepare("SELECT SUM(count) AS total, section FROM Pageviews_by_Section WHERE day <= ? and day >= ? GROUP BY section ORDER BY total DESC",$types);
$result = $stmt->execute(array($end_date_sql,$start_date_sql));
$all_data = $result->fetchAll();

foreach($all_data as $data) {
  $section = $data["section"];
  $config_file = $install_path."web/".$section."/setup.yml";
  if (file_exists($config_file)) {
  	$config = Spyc::YAMLLoad($config_file);
  	$section = $config["name"];
  }
  $popular_pages[$section] = $data["total"];
}

$popular_pages = array_slice($popular_pages, 0, 10);

// total views for today
$types = array('text');
$stmt =& $db->connection->prepare("SELECT SUM(count) AS total, day FROM Pageviews_by_Section WHERE day = ? GROUP BY day",$types);
$result = $stmt->execute(array($today_sql));
$data = $result->fetchRow();
$today_total = $data['total'];

// just providing one set of stat templates to all devices, not sexy but works
// note that i had to tweak the factory method for the page class to get this to work
require "templates/basic/statistics.html";

$page->output();

?>