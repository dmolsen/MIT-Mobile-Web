<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

# configure some map specific variables
$latitude  = 39.634419; 	# default rough latitude for your campus
$longitude = -79.954054; # default rough longitude for your campus
$mobilemap = false; 		# on static maps use mobile version of tiles

/**
 * A list of the categories for your map
 * Order their listed by here is the order they show on your main map page     
 * Format is as follows:
 *
 * cat-key         => array("text for a view title",
 * 							"text for a view title",
 *							"text for a view title",
 */
class Categorys {
  public static $info = array(
    "names"        => array("Building name", "Building Names", "Buildings by Name"),
	"dining"       => array("Dining location", "Dining Location Names", "Dining Locations"),
	"codes"        => array("Building code", "Building Codes", "Buildings by Code"),
    "wifi"         => array("WiFi-enabled location", "WiFi-enabled Locations", "WiFi-enabled Locations")
  );
}

?>