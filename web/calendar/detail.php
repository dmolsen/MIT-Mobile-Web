<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// sets up adapter class
$adapter = ModuleAdapter::find();
require_once "adapters/".$adapter."/adapter.php";

// libs
require_once "lib/calendar.lib.php";
require_once "lib/textformat.lib.php";

$id = $_REQUEST['id'];
$calkey = $_REQUEST['cal'];

$eventFeed = CalendarAdapter::getEvent($id,$calkey);
$event = $eventFeed[0]; // drop the event out of the overall array for use in the templates

if ($error == true) {
	// need to create an error handler class
	echo("<div class='error'>Error for details".$url."</div>".$e);
	exit;
} 

require "templates/$prefix/detail.html";
$page->output();

?>
