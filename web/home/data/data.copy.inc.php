<?

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/**
 * A list of the nav items you want to show on your site 
 * The order they're listed here is the order they'll show up on the site
 *
 */

/* webkit nav sections
 * $wk_items[] = array("title"     => "Title for nav link. keep it short.",
 * 						"directory" => "The directory this section is in",
 *						"extra" 	=> "if you need to specify something in a particular directory",
 * 						"icon" 		=> "icon for section. should be themes/theme_name/webkit/images/homescreen",
 *						external 	=> true or false if this should be an external link
 * 						"url" 		=> "the url to link to");
 */
$wk_items   = array();
$wk_items[] = array('Emergency','emergency','','emergency.png',false,'');
$wk_items[] = array('Calendar','calendar','','events.png',false,'');
$wk_items[] = array('Map','map','','map.png',false,'');
$wk_items[] = array('News','news','','rss.png',false,'');
$wk_items[] = array('Links','links','','links.png',false,'');
$wk_items[] = array('Directory','people','','people.png',false,'');
$wk_items[] = array('YouTube','youtube','','youtube.png',false,'');
$wk_items[] = array('Full Site','','','hedu.png',true,'http://'.$main_site_addy.'/');
$wk_items[] = array('Bookmark','mobile-about','?page=homescreen','homescreen.png',false,'');
$wk_items[] = array('Hours','hours','','hours.png',false,'');

/* basic nav sections
 * $bc_items[] = array("title"     => "Title for nav link. keep it short.",
 * 						"directory" => "The directory this section is in",
 *						"extra" 	=> "if you need to specify something in a particular directory",
 *						external 	=> true or false if this should be an external link
 * 						"url" 		=> "the url to link to");
 */
$bc_items   = array();
$bc_items[] = array('Emergency Info','emergency','',false,'');
$bc_items[] = array('Events Calendar','calendar','',false,'');
$bc_items[] = array('Campus Map','map','',false,'');
$bc_items[] = array('Campus News','news','',false,'');
$bc_items[] = array('Links','links','',false,'');
$bc_items[] = array('People Directory','people','',false,'');
$bc_items[] = array($inst_name.' on YouTube','youtube','',false,'');
$bc_items[] = array('Hours','hours','',false,'');

# for smartphones, does this site support SMS?
$has_sms = false;

/* touch nav sections
 * $to_items[] = array("title"     => "Title for nav link. keep it short.",
 * 						"directory" => "The directory this section is in",
 *						"extra" 	=> "if you need to specify something in a particular directory",
 * 						"icon" 		=> "icon for section. should be themes/theme_name/webkit/images/homescreen",
 *						external 	=> true or false if this should be an external link
 * 						"url" 		=> "the url to link to");
 */
$to_items   = array();
$to_items[] = array('Emergency Info','emergency','','emergency.png',false,'');
$to_items[] = array('Events Calendar','calendar','','calendar.png',false,'');
$to_items[] = array('Campus Map','map','','map.png',false,'');
$to_items[] = array('Campus News','news','','rss.png',false,'');
$to_items[] = array('Links','links','','links.png',false,'');
$to_items[] = array('People Directory','people','','people.png',false,'');
$to_items[] = array($inst_name.' on YouTube','youtube','','youtube.png',false,'');
$to_items[] = array('About this Site','mobile-about','','about-h.png',false,'');
$to_items[] = array('Full '.$inst_name.' Website','','','hedu.png',true,'http://'.$main_site_addy.'/');
$to_items[] = array('Bookmark<br />Mobi','mobile-about','?page=homescreen','homescreen.png',false,'');
$to_items[] = array('Hours','hours','','hours.png',false,'');

?>