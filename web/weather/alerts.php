<?

/**
 * Copyright (c) 2010 Southeast Missouri State University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// libs - need to require first because data.inc.php uses functions
require_once "lib/weather_services.php";

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

$Alert = new Weather();
$alerts = $Alert->get_weather_alerts_feed('http://www.weather.gov/alerts-beta/wwaatmget.php?x=MOC031');

$i = $_REQUEST['index']; //TODO: Add error correction if this index is not set.

//Build array for title elements.
$elements = $Alert->parseTitle($alerts[$i]['title']);

//Build updated array to show formatted date and time.
$formatDateTime = $Alert->parseUpdatedTime($alerts[$i]['updated']);

require "templates/$prefix/alerts.html";

$page->output();

?>
