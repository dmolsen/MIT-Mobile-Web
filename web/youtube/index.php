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
require "lib/youtube.lib.inc.php";

//various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

$today = day_info(time());

#$search_options = SearchOptions::get_options();

$yt = new Zend_Gdata_YouTube();
$yt->setMajorProtocolVersion(2);
$uploads = $yt->getuserUploads('westvirginiau');

require "$prefix/index.html";
$page->output();

?>
