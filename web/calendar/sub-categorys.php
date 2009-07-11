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

$category = MIT_Calendar::Category($_REQUEST['id']);
$categorys = MIT_Calendar::subCategorys($category);

if(count($categorys) == 0) {
  header("Location: " . categoryURL($category));
}

require "$prefix/sub-categorys.html";
$page->output();

?>
