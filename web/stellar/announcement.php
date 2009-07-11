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

//start session (used to save class details)
session_id($_REQUEST['sess']);
session_start();
$class = $_SESSION['class'];
$item = $class['announcements'][ $_REQUEST['index'] ];

function paragraphs($item) {
  $text = htmlentities($item['text']);

  //this hack fixes some strange encoding problem
  $text = str_replace('&Acirc;', '', $text);
  return explode("\n", $text);
}

function longDate($item) {
  return date("l, F j, Y G:i", $item['unixtime']);
}

require "$prefix/announcement.html";
$page->output();

?>
