<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class Emergency extends RSS {
  // e2campus feed for WVU
  protected $rss_url = "http://feeds.omnilert.net/rss/d014a0436fd6c76e17d4931495231bea/b8d5ae4de409bcc5b5977f77e4222413/10/3178a/2/";
}
 
class WVUTodayNews extends RSS {
  protected $rss_url = "http://wvutoday.wvu.edu/xml/news_full.xml";
}

class OITNews extends RSS {
  protected $rss_url = "http://feeds.feedburner.com/WVUOITNews";
}

class HSCNews extends RSS {
  protected $rss_url = "http://health.wvu.edu/newsreleases/news-feed.aspx";
}

class DANews extends RSS {
  protected $rss_url = "http://www.da.wvu.edu/rss.php";
}

class RSS {
  public function get_feed() {
    //get the feed
    $rss_obj = new DOMDocument();

    //turn off warnings
    $error_reporting = ini_get('error_reporting');
    error_reporting($error_reporting & ~E_WARNING);
    $rss = file_get_contents($this->rss_url);
    error_reporting($error_reporting);

    //if the rss feed fails to open return false
    if($rss === FALSE) {
      return FALSE;
    }
    if (strlen($rss) == 0) {
      return FALSE;
    }
    $rss_obj->loadXML($rss);
    $rss_root = $rss_obj->documentElement;
    $items = array();

    foreach($rss_root->getElementsByTagName('item') as $item) {    
      $title = trim(self::getTag($item, 'title')->nodeValue);
      $items[$title] = array(
	    "date"     => getdate(strtotime(self::getTag($item, 'pubDate')->nodeValue)),
        "unixtime" => strtotime(self::getTag($item, 'pubDate')->nodeValue),
	    "text"     => self::cleanText(self::getTag($item, 'description')->nodeValue)
      );
    }

    return $items;
  }

  private static function getTag($xml_obj, $tag) {
    $list = $xml_obj->getElementsByTagName($tag);
    if($list->length == 0) {
      throw new Exception("no elements of type $tag found");
    }
    if($list->length > 1) {
      throw new Exception("elements of type $tag not unique {$list->length} found");
    }
    return $list->item(0);
  }

  private static function cleanText($html) {
    //replace <br>'s with line breaks
    $html = preg_replace('/<br\s*?\/?>/', "\n", $html);

    //replace <p>'s with line breaks
    $html = preg_replace('/<\/?p>/', "\n", $html);
    $html = preg_replace('/<p\s+?.*?>/', "\n", $html);
    
    //remove all other mark-ups
    $html = preg_replace('/<.+?>/', '', $html);

    //replace all the non-breaking spaces
    $html = str_replace("&nbsp;", " ", $html);

    return trim(htmlspecialchars_decode($html, ENT_QUOTES));
  }
}

?>
