<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* News Info - NOTE: uses first entry as default entry */

/**
 * A list of the news sources you want to show on your site     
 * Format is as follows:
 *
 * $news_srcs["mobi-news-key"] = array("title"     => "Title for feed to be displayed in mobi",
 *									   "url"       => "URL for the RSS Feed",
 * 									   "need_the"  => true of false, lame hack for WVU. Puts a 'the' in front of title attribute,
 *									   "read_more" => true or false, adds a 'read more' link for rss feeds that don't have full articles);
 */

$news_srcs = array();
$news_srcs["pao"] = array("title" => "PAO Office", "url" => "http://rss.soccernet.com/c/668/f/8504/index.rss", "need_the" => false, "read_more" => true);

/**
 * A list of the news links you want to show on your site     
 * Format is as follows:
 *
 * $news_links["moblinks-key"] = array("title"     => "Title for site to be linked to in mobi",
 *									   "url"       => "URL for the site",
 * 									   "need_the"  => true of false, lame hack for WVU. Puts a 'the' in front of title attribute);
 */
$news_links = array();
$news_links["og"] = array("title" => "External News Src", "url" => "http://mobiweb.pbworks.com/", "need_the" => false);

?>