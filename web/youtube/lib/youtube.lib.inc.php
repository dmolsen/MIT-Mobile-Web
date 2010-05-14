<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// set-up Zend gData
$path = $install_path.'lib/ZendGdata-1.8.4PL1/library';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_YouTube');

function printVideoFeed($videoFeed,$phone) {
  if ($phone == 'webkit') { echo("<ul class='edgetoedge'>"); } else if ($phone == 'touch') { echo("<ul class='results'>"); }
  foreach ($videoFeed as $videoEntry) {
    printVideoEntry($videoEntry,$phone);
  }
  if (($phone == 'webkit') || ($phone == 'touch')) { echo("</ul>"); }
}

function printVideoEntry($videoEntry,$phone) {
	
  $videoThumbnails = $videoEntry->getVideoThumbnails();

  $seconds = $videoEntry->getVideoDuration();
  $mins = floor ($seconds / 60);
  $secs = $seconds % 60;
  
  if ($secs < 10) {
	$secs = "0".$secs;
  }

  $updated = getdate(strtotime($videoEntry->getUpdated()->text)); 

  if (($phone == 'webkit') || ($phone == 'touch')) {
	 echo("<li class='wrap'>");
  } else {
	 echo("<p class='focal' style='height: 62px'>");
  }
  
  $outgoing = urlencode($videoEntry->getVideoTitle());

  if ($phone == 'webkit') {
	  echo("<a href='".$videoEntry->getVideoWatchPageUrl()."' class='youtube noellipsis' data-ga='/outgoing/youtube/<?=$outgoing?>/');' target='_blank'><img src='".$videoThumbnails[1]['url']."' width='90' height='68' class='vid-tn' alt='YouTube Video Thumbnail'>".$videoEntry->getVideoTitle());
  } else {
	 echo("<a href='".$videoEntry->getVideoWatchPageUrl()."' class='youtube'><img src='".$videoThumbnails[1]['url']."' hspace=6 height=60 width=80 align=left alt='YouTube Video Thumbnail'>".$videoEntry->getVideoTitle());
}

  if ($phone == 'webkit') {
	echo("<p class='smallprint wrap' style='margin-top: 5px; text-wrap: normal'>".$mins.":".$secs." mins. | ".substr($updated['month'],0,3)." ".$updated['mday']." ".$updated['year']."</p></a>");
  } else if ($phone == 'touch') {
	echo("<span class='smallprint'><br />".$mins.":".$secs." mins. | ".substr($updated['month'],0,3)." ".$updated['mday']." ".$updated['year']."</a>");
  }
  else {
    echo("</a><span class='smallprint' style='text-decoration: none'><br />".$mins.":".$secs." mins. | ".substr($updated['month'],0,3)." ".$updated['mday']." ".$updated['year']);
  }

  if (($phone == 'webkit') || ($phone == 'touch')) {
    echo("</li>");
  } else {
    echo("</p>");
  }
  
}
