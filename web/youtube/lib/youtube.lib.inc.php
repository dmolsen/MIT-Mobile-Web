<?php

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// set-up Zend gData
$path = '/apache/htdocs/Mobi-Demo/lib/ZendGdata-1.8.4PL1/library';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_YouTube');

function printVideoFeed($videoFeed) {
  $count = 1;
  foreach ($videoFeed as $videoEntry) {
    echo "Entry # " . $count . "\n";
    printVideoEntry($videoEntry);
    echo "\n";
    $count++;
  }
}

function printVideoEntry($videoEntry) {
	
  foreach ($videoEntry->mediaGroup->content as $content) {
	  if ($content->type === "video/3gpp") {
	    $mv = $content->url;
	  }
  }

  $videoThumbnails = $videoEntry->getVideoThumbnails();

  foreach($videoThumbnails as $videoThumbnail) {
    echo $videoThumbnail['time'] . ' - ' . $videoThumbnail['url'];
    echo ' height=' . $videoThumbnail['height'];
    echo ' width=' . $videoThumbnail['width'] . "\n";
  }
  echo("<p class='focal'>");
  echo("<img src='".$videoThumbnails[1]['url']."' height=70 width=70 align=left alt='YouTube Video Thumbnail'><a href='http://www.youtube.com/watch=".$videoEntry->getVideoId()."'>".$videoEntry->getVideoTitle()."</a><span class='smallprint'><br />Duration: ".$videoEntry->getVideoDuration()."<br />Updated: ".$videoEntry->getUpdated());
  echo("</p>")
  
}