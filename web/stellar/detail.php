<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../page_builder/page_header.php";
require_once "../../../lib/trunk/stellar.php";
require_once "stellar_lib.php";

function personURL($name) {
  return "../people/?filter=" . urlencode($name);
}

function mapURL($location) {
  preg_match('/^((|W|N|E|NW|NE)\-?(\d+))/', $location, $matches);
  if($matches[3]) {
    return "../map/detail.php?selectvalues={$matches[2]}{$matches[3]}&snippets=$location";
  } else {
    return "../map/search.php?filter=" . urlencode($location);
  }
}

function selfURL($all=NULL) {
  $all = $all ? $all : $_REQUEST['all'];
  $query = http_build_query(array(
    "id"   => $_REQUEST['id'],
    "all"  => $all,
    "back" => $_REQUEST['back']
  ));
  return "detail.php?$query";
}

function announceURL($index) {
  return "announcement.php?index=$index&sess=" . session_id();
}


//start session (used to save class details)
session_start();

$class_id = $_REQUEST['id'];
$class = StellarData::get_class_info($class_id);
$_SESSION['class'] = $class;

$tabs = new Tabs(selfURL(), 'tab', array('News', 'Info', 'Staff'));
$tabs_html = $tabs->html();
$tab = $tabs->active();
$term = StellarData::get_term_text();
$back = $_REQUEST['back'];
$stellar_url = stellarURL($class_id);
$has_news = count($class['announcements']) > 0;
$has_old_news = count($class['announcements']) > 5;

if($_REQUEST['all']) {
  $all = true;
  $items = $class['announcements'];
} else {
  $all = false;
  $items = array_slice($class['announcements'], 0, 5);
}

define('MAX_TEXT', 80);

function summary($item) {
  $text = summary_string($item['text']);
  return str_replace('&Acirc;', '', $text);
}

function is_long_text($item) {
  return is_long_string($item['text']);
}

function full($item) {
  $text = htmlentities($item['text']);

  //this hack fixes some strange encoding problem
  return str_replace('&Acirc;', '', $text);
}

function sDate($item) {
  return short_date($item['date']);
}

function stellarURL($id) {
  preg_match('/^(\w+)\.(\w+)/', $id, $match);
  $course_id = $match[1];
  return "http://stellar.mit.edu/courseguide/course/" . $course_id . "/" . StellarData::get_term() . "/$id/";
}

require "$prefix/detail.html";
$page->output();

?>
