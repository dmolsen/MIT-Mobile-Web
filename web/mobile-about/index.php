<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";
require "../../config.gen.inc.php";

$static_pages = array(
  'about', 
  'requirements', 
  'credits',
  'new',
);


// dynamic pages need to include dynamics scripts
switch($_REQUEST['page']) {

  // dynamic cases
  case "statistics":
    require "statistics.php";
    break;

  // static cases
  case "requirements":
  case "new":
  case "credits":
    require "$prefix/{$_REQUEST['page']}.html";
    $page->cache();
    $page->output();
    break;

  // phone dependant cases
  case "about":
  default:
    require "$phone/about.html";
    $page->cache();
    $page->output();
}

function requirementsURL() {
  return "./?page=requirements";
}

function whatsnewURL() {
  return "./?page=new";
}


function statisticsURL() {
  return "./?page=statistics";
}

function creditsURL() {
  return "./?page=credits";
}

function homescreenURL() {
  return "./?page=homescreen";
}

?>
