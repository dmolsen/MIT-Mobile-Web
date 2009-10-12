<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

# the google maps api key for this instance of your site
$maps_api_key = "";

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
# these images can be found in web/map/images/sp_markers THOUGH...
# the options below are passed to Google Static Maps and drawn. The images in
# 'sp_markers' are copies for including outside of the map. so if you change
# the graphics below make sure you grab a copy for 'sp_markers'
function marker_type($type) {
	$markers = array(
	    "Building" 				 => "midredb",
	    "Dining" 				 => "midpurpled"
	);
	return $markers[$type];	
}

# iPhone specific marker types. the key can be either the type or subtype for the building
# these images can be found in web/map/images/ip_markers
# they were pulled from: http://code.google.com/p/google-maps-icons/
$marker_types = array();
$marker_types['Building'] 				= 'apartment';
$marker_types['Dining'] 				= 'restaurant';
$marker_types['Art'] 					= 'theater';


?>