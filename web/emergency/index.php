<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// libs - need to require first because data.inc.php uses functions
require_once "../../lib/simple-rss/simple-rss.inc.php";
require_once "lib/textformat.lib.php";

// various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

// records stats
require_once "../page_builder/page_header.php";

$emergency_message = "Coming Soon: Emergency Updates"; 

if ($show_rss == true) {
	
	$feed = new SimpleRss($emergency_rss_url, 60);
	$emergencies = $feed->GetRssObject();

	if($emergencies === False) {
	  $paragraphs = array('Emergency information is currently not available');
	} else {
	  foreach ($emergencies->aItems as $item) {
        $text = explode("\n", $item->sDescription);
        $paragraphs = array();
		foreach($text as $paragraph) {
	  		if($paragraph) {
	    		$paragraphs[] = htmlentities($paragraph);
	  		}
		}
		
		// going to have to figure out timestamp issues...
        $article_c_timestamp = strtotime($item->sDate);
        $article_f_timestamp = strtotime($item->sDate)+(60*60*48); // adding two days
        $current_timestamp = time();
        $date = date('M. jS @ g:ia',strtotime($item->sDate));
	  }

	  // handle the case that an emergency RSS feed doesn't return data until emergency (like e2campus)
	  if ($paragraphs == False) {
		$paragraphs = array("There is currently no emergency on campus.");
	  }
	}
}

if(isset($_REQUEST['contacts'])) {
  require "templates/$prefix/contacts.html";
} else if (isset($_REQUEST['extra'])) {
  require "templates/$prefix/extra.html";
} else if (isset($_REQUEST['residence'])) {
  require "templates/$prefix/residence.html";
} else if (isset($_REQUEST['schools'])) {
  require "templates/$prefix/schools.html";
} else {
  require "templates/$prefix/index.html";
}

$page->output();

?>
