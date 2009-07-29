<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";
require "../../config.gen.inc.php";

$sms_pages = array(
  'bus', 
  'dir', 
  'sos',
);


// dynamic pages need to include dynamics scripts
switch($_REQUEST['page']) {

  // static cases
  case "bus":
  case "dir":
  case "sos":
    require "$prefix/{$_REQUEST['page']}.html";
    $page->cache();
    $page->output();
    break;

  default:
    require "$phone/index.html";
    $page->cache();
    $page->output();
}

function busURL() {
  return "./?page=bus";
}

function dirURL() {
  return "./?page=dir";
}


function sosURL() {
  return "./?page=sos";
}

?>
