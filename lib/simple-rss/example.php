<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>RSS Feeds Example</title>
	<style type="text/css">
	   li a { display: block; }
	   li span { color: #f90; font-size: 85%; }
	</style>
</head>

<body>
   <h1>RSS Feeds Example</h1>
	<?php
	   require('simple-rss.inc.php');
	   
	   $aFeeds = array(
	      'http://feeds.feedburner.com/ejeliot/blog-rss' => 300
	   );
	   
	   $aRssObjects = array();
	   
	   foreach ($aFeeds as $sUrl => $vCacheTime) {
	      $oRss = new SimpleRss($sUrl, $vCacheTime);

         if ($oRssObject = $oRss->GetRssObject()) {
            echo "<h2>{$oRssObject->oChannel->sTitle}</h2><ul>";
            foreach ($oRssObject->aItems as $oItem) {
               echo "<li><a href=\"$oItem->sLink\">$oItem->sTitle</a>$oItem->sAuthor <span>$oItem->sDate</span></li>";
            }
            echo '</ul>';
            if ($oRss->IsCached()) {
               echo '<p>This feed is cached.</p>';
            }
            if ($oRss->IsStaleCache()) {
               echo '<p>Feed could not be retrieved / parsed. Content displayed from stale cache.';
            }
         }
	   }
   ?>
</body>

</html>