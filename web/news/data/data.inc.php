<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* News Info - uses first entry as default entry */
$news_srcs = array();
$news_srcs['wvutoday'] = array('title' => 'WVU Today', 'url' => 'http://wvutoday.wvu.edu/n/rss/', 'need_the' => false, 'read_more' => true);
$news_srcs['hsc']      = array('title' => 'HSC', 'url' => 'http://health.wvu.edu/newsreleases/news-feed.aspx', 'need_the' => false, 'read_more' => true);
$news_srcs['oit']      = array('title' => 'OIT', 'url' => 'http://oit.wvu.edu/news/feed/', 'need_the' => false, 'read_more' => true);

$news_links["da"]  = array("title" => "Daily Athenaeum", "url" => "http://www.thedaonline.com", "need_the" => true);
$news_links["msn"] = array("title" => "MSNsportsNET.com", "url" => "http://mobile.msnsportsnet.com/", "need_the" => false);

?>