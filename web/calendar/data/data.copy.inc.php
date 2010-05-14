<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/**
 * The Events Calendar utilizes Google Calendar as it's datasource.   
 * As such you need to provide information here from Google Calendar   
 */

/* The username/password of the Google Account with the calendar items */
$username = "google_username";
$password = "google_password";

/**
 * A list of the calendar categories you want to show on your site     
 * Format is as follows:
 *
 * $calendars["mobi-cal-key"] = array("title" => "Text to be displayed in mobi",
 * 										"user"  => "Calendar ID from Google Cal for this calendar",
 *										"main"  => true or false it should be on main cal nav,
 *										"caltxt"=> true or false 'calendar' should be added after title in nav); 
 */

$calendars = array();

/* required to have an "all" category for search purposes */
$calendars["all"] 			= 	array("title" => "All Events", 
									  "user"  => "pcnqpk03212bhbvvq03hc0ouv8k0oj76@import.calendar.google.com",
									  "main"  => false,
									  "caltxt"=> false);

/* any other categorys you want */
$calendars["academic"] 		= 	array("title" => "Academics", 
									  "user"  => "7q0f77k0oi0uiet6el58htdptdi1f4r5@import.calendar.google.com",
									  "main"  => true,
									  "caltxt"=> true);
$calendars["academic"] 		= 	array("title" => "Academics", 
									  "user"  => "7q0f77k0oi0uiet6el58htdptdi1f4r5@import.calendar.google.com",
									  "main"  => true,
									  "caltxt"=> true);	
																	
?>
