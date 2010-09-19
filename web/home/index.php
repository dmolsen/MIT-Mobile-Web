<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* NOTE THAT THIS FILE ONLY SERVES TOUCH & BASIC DEVICES NOW THOUGH WEBKIT WILL WORK    */
/* WEBKIT IS NOW HANDLED BY THE ROOT INDEX FILE TO MAKE LIFE WITH MANIFEST-CACHE EASIER */

require "../../config.gen.inc.php";
require "../page_builder/Page.php";
require "../page_builder/counter.php";
require "data/data.inc.php";

$phone = Page::classify_phone();
$prefix = Page::$requireTable[$phone];
$page = Page::factory($phone);

PageViews::increment('home');

if ($phone == 'basic') {
	$width = 48;
	$height = 19;
}

if ($_REQUEST['more'] != true) {
	require "templates/$prefix/index.html";
}
else {
	require "templates/$prefix/more.html";
}


?>
