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

function selfURL() {
  return "course.php?id=" . $_REQUEST['id'] . '&back=' . $_REQUEST['back'];
}

$id = $_REQUEST['id'];
$back = $_REQUEST['back'];
$Back = ucwords($back);

$course = StellarData::get_course($id);
$classes = StellarData::get_classes($id);
$has_classes = count($classes) > 0;

require "$prefix/course.html";
$page->output();
    
?>
