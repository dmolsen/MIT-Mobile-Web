<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../config.gen.inc.php";
require_once "page_builder/Page.php";
require_once "page_builder/detection.php";
require_once "page_builder/counter.php";
require_once "home/data/data.inc.php";

$phone = Device::templates();
$page = Page::factory($phone);

// lots of funky stuff needs to happen if the device is webkit to support the jqtouch themes
if ($phone == 'webkit') {
	
	// this ugliness? it's to support deep linking. 
	// done more out of a frustration than care & prolly needs to be redone
	// the h var handles when a user clicks on the 'home' button on a deep link. the header & footer don't need to be loaded again.
	if (!$_REQUEST['h']) { require "home/templates/webkit/header.html"; }
	
	// if a request was for a deep link on a webkit device it got bounced to home, so do something with it
	if ($_REQUEST['redirect']) {
		
		$redirect = $_REQUEST['redirect'];
		
		// don't allow "protected" sections to be shown at all and just kick to the homescreen view of true
		if (!preg_match("/(\/lib\/|\/page_builder\/|\/themes\/|\/templates\/|\/data\/)/i",$redirect)) {
			
			// add the internal request [ir] var to the query so the header & footer aren't added again
			// dl var is used to get the toolbar.html template to show 'home' instead of 'back'
			if (strstr($redirect,"?")) {
				$redirect .= "&ir=true&dl=true";
			} else {
				$redirect .= "?ir=true&dl=true";
			}
			
			// use the user agent supplied by the device to properly get the content & record the hit
			ini_set('user_agent', $_SERVER['HTTP_USER_AGENT']);
			$data = file_get_contents("http://".$mobile_web_addy.$redirect);
			
			// shove the response to the user
			echo($data);
			
		} else {
			PageViews::increment('home');
			require "home/templates/webkit/index.html";
		}	
	} else {
		PageViews::increment('home');
		require "home/templates/webkit/index.html";
	}
	if (!$_REQUEST['h']) { require "home/templates/webkit/footer.html"; }
	
} else if (Device::is_computer() || Device::is_spider()) {
	header("Location: /about/");
} else {
	header("Location: /home/");
}

?>
