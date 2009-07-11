<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

#echo("http://asdb-cluster.wvu.edu:8002/wurfl/api?UserAgent=" . urlencode($_SERVER['HTTP_USER_AGENT']);

require "page_builder/Page.php";

Page::classify_phone();

if(Page::is_computer() || Page::is_spider()) {
  header("Location: /about/");
} else {
  header("Location: /home/");
}
?>
