<?

/**
 * Copyright (c) 2010 Southeast Missouri State University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require "../page_builder/page_header.php";
require_once "../../lib/weather_services.php";
require_once "../../config.gen.inc.php";



	function alertURL( $warning, $arrayIndex ){
		$url = 'alerts.php?warning='.$warning.'&index='.$arrayIndex;
		return $url;
	}

	function getWarning( $title ){
		$string = explode("issued", $title);
		$warning = $string[0];
		
		return $warning;
	}
	
	$Alert = new Weather();
	$alerts = $Alert->get_weather_alerts_feed('http://www.weather.gov/alerts-beta/wwaatmget.php?x=MOC031');
	//print_r( $alerts );
	
	$Current = new Weather();
	$conditions = $Current->get_current_conditions_feed('http://www.weather.gov/xml/current_obs/KCGI.xml');

	require "$prefix/index.html";

$page->help_off();
$page->output();

?>