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

// page, title, campus, description, sun, mon, tue, wed, thur, fri, sat
$hours_locations = array();
$hours_locations['cl_dc_whitehall'] = array("whitehall","White Hall Computing Lab","Downtown","The Downtown lab is located in White Hall on the corner of Willey Street and University Avenue and right next to Wise Library.","2pm-12am","8:15am-12am","8:15am-12am","8:15am-12am","8:15am-12am","8:15am-6pm","10am-6pm");
$hours_locations['cl_ec_evansdale'] = array("evansdale","Evansdale Computing Lab","Evansdale","The Evansdale Computing Lab (Evansdale Library G14) is on the ground floor of the Evansdale Library , but accessible through a separate entrance only on the side facing the Agricultural Sciences Building. ","2pm-12am","8am-12am","8am-12am","8am-12am","8am-12am","8am-6pm","10am-6pm");
$hours_locations['cl_dc_whitehall'] = array("erc","ERC Computing Lab","Evansdale","The ERC Computing Lab is in the basement of Bennett Tower in the Evansdale Residential Complex (ERC), also known as “Towers.” ","2pm-12am","8am-12am","8am-12am","8am-12am","8am-12am","8am-6pm","10am-6pm");

$days = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");

// dynamic pages need to include dynamics scripts
switch($_REQUEST['page']) {

  $i = 0;
  
  // overall computer labs cases
  case "cl":
    require "$phone/cl/index.html"
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

?>
