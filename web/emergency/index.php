<?

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// libs - need to require first because data.inc.php uses functions
require_once "../../lib/rss_services.php";
require_once "lib/textformat.lib.php";

// various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

// records stats
require_once "../page_builder/page_header.php";

$emergency_message = "Coming Soon: Emergency Updates"; 

if ($show_rss == true) {
	$Emergency = new RSS();
	$emergencies = $Emergency->get_feed($emergency_rss_feed);

	if($emergencies === False) {
	  $paragraphs = array('Emergency information is currently not available');
	} else {
	  foreach ($emergencies as $title => $emergency) {
        $text = explode("\n", $emergency['text']);
        $paragraphs = array();
		foreach($text as $paragraph) {
	  		if($paragraph) {
	    		$paragraphs[] = htmlentities($paragraph);
	  		}
		}
        $article_c_timestamp = mktime($emergency['date']['hours'],$emergency['date']['minutes'],$emergency['date']['seconds'],$emergency['date']['mon'],$emergency['date']['mday'],$emergency['date']['year']);
        $article_f_timestamp = mktime($emergency['date']['hours'],$emergency['date']['minutes'],$emergency['date']['seconds'],$emergency['date']['mon'],$emergency['date']['mday']+2,$emergency['date']['year']);
        $current_timestamp = time();
        $date = short_date($emergency['date']);
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
