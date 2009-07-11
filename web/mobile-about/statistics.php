<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


$all_data = PageViews::past_days(7);


// find the largest number of views in the days
$max_views = 0;
foreach($all_data as $day) {
  if($day['total'] > $max_views) {
    $max_views = $day['total'];
  }
}

// determine the maximum to use for the bar graph
$limits = array(1, 2, 4, 5);

$found = False;
$scale = 10;
while(!$found) {
  foreach($limits as $limit) {
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
foreach($all_data as $day) {
  $views[] = array(
    'day' => $day['name'],
    'date' => $day['date'],
    'count' => $day['total'],
    'percent' => per_cent($day['total'], $max_scale),
  );
}


// views by device
$traffic = array(
  "iPhone" => 0,
  "Smartphone" => 0,
  "Feature Phone" => 0,
  "Other" => 0,
);
foreach($all_data as $day) {
  $traffic["iPhone"] += $day["ip"];
  $traffic["Smartphone"] += $day["sp"];
  $traffic["Feature Phone"] += $day["fp"];
  $traffic["Other"] += $day["computer"];
}
$total = 0;
foreach($traffic as $count) {
  $total += $count;
}
$phone_traffic = array();
foreach($traffic as $device => $count) {
  $phone_traffic[$device] = per_cent($count, $total);
}
 

$urls = array(
  'people' => 'People Directory',
  'map' => 'Campus Map',
  'shuttleschedule' => 'Shuttle Schedule',
  'calendar' => 'Events Calendar',
  'stellar' => 'Stellar',
  'careers' => 'Student Career Services',
  'emergency' => 'Emergency Info',
  '3down' => '3DOWN',
  'links' => 'Useful Links',
  'mobile-about' => 'About this Site',
);

$popular_pages = array();
foreach($urls as $url => $name) {
  $content_total = 0;
  foreach($all_data as $day) {
    $content_total += $day[PageViews::url2db($url)];
  }

  $popular_pages[] = array(
    'name' => $name,
    'link' => $url,
    'count' => $content_total,
  );
}

function compare_content($content1, $content2) {
  if($content1['count'] < $content2['count']) {
    return 1;
  }
  if($content1['count'] > $content2['count']) {
    return -1;
  }
  return 0;
}
usort($popular_pages, 'compare_content');
$popular_pages = array_slice($popular_pages, 0, 5);

$start_time = PageViews::$time - 7 * 24 * 60 * 60;
$end_time = PageViews::$time - 1 * 24 * 60 * 60;

$start_month = date('M', $start_time);
$start_day = date('j', $start_time);

$end_month = date('M', $end_time);
$end_day = date('j', $end_time);

$year = date('Y', $end_time);

if($start_month == $end_month) {
  $days_text = "{$start_month} {$start_day}-{$end_day}, $year"; 
} else {
  $days_text = "{$start_month} {$start_day}-{$end_month} {$end_day}, $year"; 
}

require "$prefix/statistics.html";
$page->output();


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

?>