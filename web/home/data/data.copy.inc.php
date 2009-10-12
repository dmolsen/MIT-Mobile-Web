<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/**
 * A list of the nav items you want to show on your site 
 * The order they're listed here is the order they'll show up on the site
 * On the smartphone/feature phone view they'll be split by nines not counting hidden sections   
 * Format is as follows:
 *
 * $items["navkey"] = array(show_ip     => true or false that this section should show on iPhone,
 *							show_sp		=> true or false that this section should show on smartphones and feature phones,
 *							"title"     => "Title for nav link. keep it short.",
 * 							"directory" => "The directory this section is in",
 *							"extra" 	=> "if you need to specify something in a particular directory",
 * 							"icon" 		=> "icon for section. should be web/home/ip/images"
 *							external 	=> true or false if this should be an external link
 * 							"url" 		=> "the url to link to");
 */

# base sections
$items = array();
$items["nav1"]  = array(true,true,'Emergency Info','emergency','','emergency.png',false,'');
$items["nav2"]  = array(true,true,'Events Calendar','calendar','','calendar.png',false,'');
$items["nav3"]  = array(true,true,'Campus Map','map','','map.png',false,'');
$items["nav4"]  = array(true,true,'Campus News','news','','rss.png',false,'');
$items["nav5"]  = array(true,true,'Links','links','','links.png',false,'');
$items["nav6"]  = array(true,true,'People Directory','people','','people.png',false,'');
$items["nav7"]  = array(true,true,$inst_name.' on YouTube','youtube','','youtube.png',false,'');

# iphone only sections
$items["nav8"]  = array(true,false,'About this Site','mobile-about','','about-h.png',false,'');
$items["nav9"]  = array(true,false,'Full '.$inst_name.' Website','','','hedu.png',true,'http://'.$main_site_addy.'/');
$items["nav10"] = array(true,false,'Bookmark<br />Mobi','mobile-about','?page=homescreen','homescreen.png',false,'');

# hidden sections
$items["nav11"] = array(false,false,$inst_name.' SMS<br />(BETA)','sms','','sms.png',false,'');
$items["nav12"] = array(false,false,'Athletics','gameday','','gameday.png',false,'');
$items["nav13"] = array(false,false,'Hours','hours','','hours.png',false,'');
$items["nav14"] = array(false,false,'Campus Radio','radio','','u92.png',false,'');
$items["nav15"] = array(false,false,'Mountain Line','shuttleschedule','','shuttletrack.png',false,'');

# for smartphones, does this site support SMS?
$has_sms = false;

?>