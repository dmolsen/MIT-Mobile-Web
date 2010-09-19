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
require_once "data/data.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// libs
require_once "lib/youtube.lib.inc.php";

if ((int)$_REQUEST['page'] != 0) {
  $prev = $_REQUEST['page'] - 1;
  $next = $_REQUEST['page'] + 1;
  $index = ($_REQUEST['page']*5)-4;
}
else {
  $next = 2;
  $index = 1;
}

$yt = new Zend_Gdata_YouTube();
$yt->setMajorProtocolVersion(2);
$query = $yt->newVideoQuery();
$query->setMaxResults(5);
$query->setAuthor($youtube_user);
$query->setOrderBy('updated');
$query->setStartIndex($index);
$uploads = $yt->getVideoFeed($query);

require "templates/$prefix/index.html";
$page->output();

?>
