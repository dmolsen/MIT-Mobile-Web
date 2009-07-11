<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";
require "../../../lib/trunk/mit_calendar.php";
require "calendar_lib.php";

$categorys = MIT_Calendar::Categorys();

require "$prefix/categorys.html";
$page->output();

?>
