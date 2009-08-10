<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../lib/rss_services.php";
require_once "data/data.inc.php";

$Emergency = new RSS();
$emergencies = $Emergency->get_feed($emergency_rss_feed);

if($emergencies === False) {
  echo('Emergency information is currently not available');
} else {
  foreach ($emergencies as $title => $emergency) {
	$text = explode("\n", $emergency[$title]['text']);
	$paragraphs = array();
	foreach($text as $paragraph) {
	  if($paragraph) {
	    echo($paragraph);
	  }
	}
  }

  // handle the case that an emergency RSS feed doesn't return data until emergency (like e2campus)
  if ($paragraphs == False) {
	echo("There is currently no emergency on campus.");
  }
}

?>
