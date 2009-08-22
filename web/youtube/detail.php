<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";

// various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

// sets up google calendar classes
require "lib/gcalendar_setup.php";

// defines all the variables related to being today
require "lib/calendar_lib.php";

require "$prefix/detail.html";
$page->output();

?>
