<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require "../config.gen.inc.php";
require "page_builder/Page.php";
require "page_builder/counter.php";
require "home/data/data.inc.php";

$phone = Page::classify_phone();
$prefix = Page::$requireTable[$phone];
$page = Page::factory($phone);

# to support manifest-cache we have to load home at the root for webkit devices
if ($prefix == 'webkit') {
	PageViews::increment('home');
	require "home/templates/$prefix/index.html";
} else if (Page::is_computer() || Page::is_spider()) {
	header("Location: /about/");
} else {
	header("Location: /home/");
}

?>
