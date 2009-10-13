<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* The Events Calendar utilizes Google Calendar as it's datasource.    */
/* As such you need to provide information here from Google Calendar   */

/* The username/password of the Google Account with the calendar items */
$username = "wvucalendar2";
$password = "Ars3nal#10";

/* A list of the calendar categories you want to show on your site     
 * Format is as follows:
 *   $calendars["mobi-cal-key"] = array("title" => "Text to be displayed in mobi",
 * 										"user"  => "Calendar ID from Google Cal for this calendar"); */

$calendars = array();
$calendars["all"] 			= 	array("title" => "All Events", 
									  "user"  => "pcnqpk03212bhbvvq03hc0ouv8k0oj76@import.calendar.google.com"
									  "main"  => false,
									  "caltxt"=> false);
$calendars["academic"] 		= 	array("title" => "Academics", 
									  "user"  => "7q0f77k0oi0uiet6el58htdptdi1f4r5@import.calendar.google.com",
									  "main"  => true,
									  "caltxt"=> true);
$calendars["ae"] 			= 	array("title" => "Arts &amp; Entertainment", 
									  "user"  => "jo17k9ojg8uab1uq7alqvu9jmgdl57u6@import.calendar.google.com",
									  "main"  => true,
									  "caltxt"=> true);
$calendars["athletics"] 	= 	array("title" => "Athletics", 
									  "user"  => "5jv3ginmvhhb51n6t92r24bp4v2nedi0@import.calendar.google.com",
									  "main"  => true,
									  "caltxt"=> true);
$calendars["cac"] 			= 	array("title" => "CAC Events", 
									  "user"  => "esjpv518csk1rtq4uejk835gsevd8gpp@import.calendar.google.com",
									  "main"  => false,
									  "caltxt"=> false);
$calendars["clubsports"] 	= 	array("title" => "Club Sports", 
									  "user"  => "b70vrbjo0drssr5cmkvfiadve9v63of0@import.calendar.google.com"
									  "main"  => false,
									  "caltxt"=> false);
$calendars["lectures"] 		= 	array("title" => "Lectures &amp; Speakers", 
									  "user"  => "a9cfmqkh0f99nl7hmggs0ialadu2u873@import.calendar.google.com",
									  "main"  => false,
									  "caltxt"=> false);
$calendars["outdoor"] 		= 	array("title" => "Outdoor Recreation", 
									  "user"  => "0ej1apou417g9m7g277k8qsq95f6lfpn@import.calendar.google.com",
									  "main"  => false,
									  "caltxt"=> false);
$calendars["special_events"] = 	array("title" => "Special Events", 
									  "user"  => "1aedcokhldd1ea16m3m0mdt14nmljkss@import.calendar.google.com",
									  "main"  => false,
									  "caltxt"=> false);
$calendars["holidays"] 		= 	array("title" => "University Holidays", 
									  "user"  => "sacn0sc5j9sq8g7o2rpcb1hfbl8t99ep@import.calendar.google.com",
									  "main"  => true,
									  "caltxt"=> true);

?>
