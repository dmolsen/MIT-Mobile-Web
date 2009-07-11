<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";

switch($_REQUEST['code']) {
  case "data":
    $message = "We are sorry the server is currently experiencing errors. Please try again later.";
     break;

  case "internal":
    $message = "Internal Server Error"; 
    break;

  case "notfound":
    $message = "URL Not Found";
    break;
}

$url = $_REQUEST['url'];
require "$prefix/index.html";

$page->help_off();
$page->output();

?>
