<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require "../page_builder/page_header.php";
require_once "../../lib/rss_services.php";
require_once "../../config.gen.inc.php";

if ($_REQUEST['page'] == 'radar') {
	require "$prefix/radar.html";
}
else if ($_REQUEST['page'] == 'forecast') {
	require "$prefix/forecast.html";
}
else {
	$Alert = new RSS();
	$alerts = $Alert->get_feed('http://www.weather.gov/alerts-beta/wwaatmget.php?x=WVZ022');
	
	$Current = new RSS();
	$conditions = $Current->get_feed('http://www.weather.gov/xml/current_obs/KMGW.rss');
	
	require "$prefix/index.html";
}


$page->help_off();
$page->output();

?>