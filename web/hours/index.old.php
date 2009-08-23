<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";
require "../../config.gen.inc.php";
require "data/data.inc.php";

$days = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
$i = 0;

// dynamic pages need to include dynamics scripts
switch($_REQUEST['page']) {
  
  // overall computer labs cases
  case "cl":
    require "$prefix/cl/index.html";
    $page->cache();
    $page->output();
    break;

  // specific computer labs cases
  case "cl_dc_whitehall":
  case "cl_ec_evansdale":
  case "cl_ec_erc":
    require "$prefix/cl/details.html";
    $page->cache();
    $page->output();
    break;

  default:
    require "$phone/index.html";
    $page->cache();
    $page->output();
}

// need to fix this area
function cldcwhitehallURL() {
  return "./?page=cl_dc_whitehall";
}

function clecevansdaleURL() {
  return "./?page=cl_ec_evansdale";
}

function clecercURL() {
  return "./?page=cl_ec_erc";
}

function clURL() {
  return "./?page=cl";
}

?>
