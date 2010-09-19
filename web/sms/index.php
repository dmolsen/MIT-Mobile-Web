<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

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
    require "templates/$prefix/{$_REQUEST['page']}.html";
    $page->cache();
    $page->output();
    break;

  default:
    require "templates/$phone/index.html";
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
