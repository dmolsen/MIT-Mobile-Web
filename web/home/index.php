<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/Page.php";
require "../page_builder/counter.php";
require "../../config.gen.inc.php";
require "data/data.inc.php";

$phone = Page::classify_phone();
$prefix = Page::$requireTable[$phone];
$page = Page::factory($phone);

PageViews::increment('home');

switch($phone) {
  case "sp":
    $width = 48;
    $height = 19;
    break;

  case "fp":
    $width = 36;
    $height = 16;
    break;
}

$page->cache();

if ($_REQUEST['more'] != true) {
	require "templates/$prefix/index.html";
}
else {
	require "templates/$prefix/more.html";
}


?>
