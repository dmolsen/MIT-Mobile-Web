<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../page_builder/page_header.php";
require_once "../../../lib/trunk/rss_services.php";

$ThreeDown = new ThreeDown();
$states = $ThreeDown->get_feed();
$title = $_REQUEST['title'];
$text = explode("\n", $states[$title]['text']);
$paragraphs = array();
foreach($text as $paragraph) {
  if($paragraph) {
    $paragraphs[] = htmlentities($paragraph);
  }
}


$long_date = date("l, F j, Y G:i:s", $states[$title]['unixtime']);

require "$prefix/detail.html";
$page->output();
    
?>
