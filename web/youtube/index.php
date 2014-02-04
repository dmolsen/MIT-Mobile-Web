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
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_YouTube');

require_once "lib/youtube.lib.inc.php";
require_once($library_path . DIRECTORY_SEPARATOR . 'cache.php');

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

$cache = mwosp_get_cache();

$cache_id = md5($query->getQueryUrl());
$uploads = array();

// XXX: Workaround for deserialization
Zend_Loader::loadClass('Zend_Http_Client_Adapter_Socket');

if (($uploads = $cache->load($cache_id)) === false) {
  $uploads = $yt->getVideoFeed($query);
  $cache->save($uploads, $cache_id);
}

require "templates/$prefix/index.html";
$page->output();

?>
