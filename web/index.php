<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require "../config.gen.inc.php";
require "page_builder/Page.php";
require "page_builder/detection.php";
require "page_builder/counter.php";
require "home/data/data.inc.php";

$phone = $prefix = Device::templates();
$page = Page::factory($phone);

# to support manifest-cache we have to load home at the root for webkit devices
if ($phone == 'webkit') {
	PageViews::increment('home');
	require "home/templates/$prefix/index.html";
} else if (Device::is_computer() || Device::is_spider()) {
	header("Location: /about/");
} else {
	header("Location: /home/");
}

?>
