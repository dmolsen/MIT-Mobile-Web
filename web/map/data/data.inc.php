<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

# the google maps api key for this instance of your site
$maps_api_key = "ABQIAAAAgl5MtLeiQwCMBX7FdoPP_BTfAZWzJoh_gYMfdqhKwTyraOPtpRSIZm3YBA6TbcecvlyiMX_gNejDzg";

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
    "campus"       => array("Campus", "Building Names", "Buildings by Campus", "Building"),
    "codes"        => array("Building code", "Building Codes", "Buildings by Code", "Building"),
    "athletics"    => array("Athletic Facility name", "Athletic Facility Names", "Athletic Facilities", "Athletic Facility"),
    "computer"     => array("Computer Lab name", "Computer Lab Names", "Computer Labs", "Computer Lab"),
	"dining"       => array("Dining location", "Dining Location Names", "Dining Locations", "Dining"),
	"library"      => array("Library name", "Library Names", "Libraries", "Library"),
    "prt"          => array("PRT Station", "PRT Station Names", "PRT Stations", "PRT Station"),
    "res"          => array("Residence Hall name", "Residence Hall Names", "Residence Halls", "Housing"),
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
	    "Health Sciences Center" => "midredb",
	    "Dining" 				 => "midpurpled",
	    "Parking Lot" 			 => "midbluep",
	    "PRT Station" 			 => "midblackp",
	    "Bus Stop" 				 => "midgreens",
	    "Athletic Facility" 	 => "midyellowa",
	    "Library" 				 => "midgrayl",
	    "Housing" 				 => "midbrownr",
	    "Computer Lab" 			 => "midbluec"
	);
	return $markers[$type];	
}

# iPhone specific marker types. the key can be either the type or subtype for the building
# these images can be found in web/map/images/ip_markers
# they were pulled from: http://code.google.com/p/google-maps-icons/
$marker_types = array();
$marker_types['Building'] 				= 'apartment';
$marker_types['Parking'] 				= 'parking';
$marker_types['Housing'] 				= 'home';
$marker_types['Athletic Facility'] 		= 'track';
$marker_types['Dining'] 				= 'restaurant';
$marker_types['Health Sciences Center'] = 'apartment';
$marker_types['PRT Station'] 			= 'train';
$marker_types['Computer Lab']			= 'computer';
$marker_types['Alumni Center'] 			= 'apartment';
$marker_types['Library'] 				= 'library';
$marker_types['Rec'] 					= 'pool';
$marker_types['Health'] 				= 'doctor';
$marker_types['Visitor'] 				= 'info';
$marker_types['Children'] 				= 'playground';
$marker_types['Student'] 				= 'clothes';
$marker_types['Track'] 					= 'track';
$marker_types['Tennis'] 				= 'tennis';
$marker_types['Football'] 				= 'rugby';
$marker_types['Baseball'] 				= 'baseball';
$marker_types['Soccer'] 				= 'soccer';
$marker_types['Basketball'] 			= 'basketball';
$marker_types['Newspaper'] 				= 'text';
$marker_types['Art'] 					= 'theater';
$marker_types['Green'] 					= 'flowers';
$marker_types['Hospital'] 				= 'hospital';
$marker_types['Admissions'] 			= 'university';
$marker_types['Wrestling'] 				= 'wrestling';
$marker_types['Gymnastics'] 			= 'gymnastics';
$marker_types['Swimming'] 				= 'pool';

?>