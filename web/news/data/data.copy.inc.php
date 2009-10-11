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
$news_srcs["pao"] = array("title" => "PAO Office", "url" => "http://pao.inst.edu/rss/", "need_the" => false, "read_more" => true);

$news_links["og"] = array("title" => "Ext News Src", "url" => "http://athletics.inst.edu/", "need_the" => false);

?>