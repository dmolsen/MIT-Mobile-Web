<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../page_builder/page_header.php";
require_once "../../../lib/trunk/stellar.php";
require_once "stellar_lib.php";

$which = $_REQUEST['which'];

if($which == "other") {
  $courses = StellarData::get_others();
  $title = "Other Courses";
} else {
  $all_courses = StellarData::get_courses();
  $drill = new DrillNumeralAlpha($which, "key");
  $courses = $drill->get_list($all_courses);
  $title = "Courses $which";
}

require "$prefix/courses.html";

$page->output();
    
?>
