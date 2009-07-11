<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";

//defines all the variables related to being today
require "calendar_lib.php";

$today = day_info(time());

$search_options = SearchOptions::get_options();

require "$prefix/index.html";
$page->output();

?>
