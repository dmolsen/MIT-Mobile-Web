<?

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
 *							true or false is it a searchable type
 */
class Categorys {
  public static $info = array(
    "names"        => array("Building name", "Building Names", "Buildings by Name", "Building"),
	"dining"       => array("Dining location", "Dining Location Names", "Dining Locations", "Dining"),
    "wifi"         => array("WiFi-enabled location", "WiFi-enabled Locations", "WiFi-enabled Locations", "WiFi-enabled Location")
  );
}

# Smartphone and feature phone specific marker types. key has to be a type and not a subtype
# these images can be found in web/themes/[theme_name]/basic/images/markers THOUGH...
# the options below are passed to Google Static Maps and drawn. The images in
# 'markers' are copies for including outside of the map. so if you change
# the graphics below make sure you grab a copy for 'markers'
function marker_type($type) {
	$markers = array(
	    "Building" 				 => "midredb",
	    "Dining" 				 => "midpurpled"
	);
	return $markers[$type];	
}

# iPhone specific marker types. the key can be either the type or subtype for the building
# these images can be found in web/themes/[theme_name]/webkit/images/markers
# they were pulled from: http://code.google.com/p/google-maps-icons/
$marker_types = array();
$marker_types['Building'] 				= 'apartment';
$marker_types['Dining'] 				= 'restaurant';
$marker_types['Art'] 					= 'theater';


?>