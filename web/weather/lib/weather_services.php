<?php

/**
 * Copyright (c) 2010 Southeast Missouri State University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class Weather {
  public function get_weather_alerts_feed($rss_url) {
    //get the feed
    $rss_obj = new DOMDocument();

    //turn off warnings
    $error_reporting = ini_get('error_reporting');
    error_reporting($error_reporting & ~E_WARNING);
    $rss = file_get_contents($rss_url);
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
    
	$alerts = array();
    foreach($rss_root->getElementsByTagName('entry') as $entry) { 

      $alert = array(

		"title" => trim( self::getTag($entry, 'title')->nodeValue ),
	    "link"     => self::getTag($entry, 'link')->getAttribute('href'),
		"updated"  => self::getTag($entry, 'updated')->nodeValue,
		
	  );
	  array_push( $alerts, $alert );
    }
	
    return $alerts;
  }
  
  
  public static function get_current_conditions_feed($rss_url) {
  
    //get the feed
    $rss_obj = new DOMDocument();

    //turn off warnings
    $error_reporting = ini_get('error_reporting');
    error_reporting($error_reporting & ~E_WARNING);
    $rss = file_get_contents($rss_url);
    error_reporting($error_reporting);

    //if the rss feed fails to open return false
    if($rss === FALSE) {
      return FALSE;
    }
    if (strlen($rss) == 0) {
      return FALSE;
    }
	
    $rss_obj->loadXML($rss);

    foreach($rss_obj->getElementsByTagName('current_observation') as $current) { 
	
      $condition = array(

		"icon_url_base" => self::getTag( $current, 'icon_url_base' )->nodeValue,
		"icon_url_name" => self::getTag( $current, 'icon_url_name' )->nodeValue,
		"temp_f" => self::getTag( $current, 'temp_f' )->nodeValue,
		"weather" => self::getTag( $current, 'weather' )->nodeValue,
		"windchill_f" => self::getTag( $current, 'windchill_f' )->nodeValue,
		"heat_index_f" => self::getTag( $current, 'heat_index_f' )->nodeValue,
		"relative_humidity" => self::getTag( $current, 'relative_humidity' )->nodeValue,
		"wind_string" => self::getTag( $current, 'wind_string' )->nodeValue,
		"wind_dr" => self::getTag( $current, 'wind_dr' )->nodeValue,
		"wind_mph" => self::getTag( $current, 'wind_mph' )->nodeValue,
		"pressure_in" => self::getTag( $current, 'preasure_in' )->nodeValue,
		"dewpoint_f" => self::getTag( $current, 'dewpoint_f' )->nodeValue,
		"observation_time" => self::getTag( $current, 'observation_time' )->nodeValue,	
	  );
    }
	return $condition;
  
  }
    
  private static function getTag($xml_obj, $tag) {
    $list = $xml_obj->getElementsByTagName($tag);
    if($list->length == 0) {
      #throw new Exception("no elements of type $tag found");
    }
    if($list->length > 1) {
      #throw new Exception("elements of type $tag not unique {$list->length} found");
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
  

  public static function parseTitle( $title ){
	
	$parts = array();
	//Title Formatting
	//$title = self::getTag($alerts, 'title')->nodeValue;
	
	$string = explode("issued", $title);
	//$warning = $string[0];
	$parts['warning'] = $string[0];
	
	$string = explode("expiring", $string[1]);
	//$issued = $string[0];
	$parts['issued'] = $string[0];
	
	$string = explode("by", $string[1]);
	//$expiring = $string[0];
	$parts['expiring'] = $string[0];
	
	$string = explode(" ", $string[1]);
	$count = count( $string );
	
	$issuedBy = $orgURL = "";	
	for( $i = 0; $i < $count-1; $i++ ){
		$issuedBy .= $string[$i] . " ";
		if( $i == $count-2 ){
			$orgURL = $string[$i+1];
		}
	}
	$parts['issuedBy'] = $issuedBy;
	$parts['orgURL'] = $orgURL;
		
	return $parts;
  }
	
  public static function parseUpdatedTime( $updated ){
	$parts = array();
		
	$list = date_parse( $updated );  //Builds array

	$date = date('M d, Y', mktime(0, 0, 0,$list['month'], $list['day'], $list['year']));
	$time = date('g:i a T' ,mktime($list['hour'],$list['minute'],0,0,0,0) );
		
	$parts['date'] = $date;
	$parts['time'] = $time;
		
	return $parts;
  }
}
?>
