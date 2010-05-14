<?
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

require("../../config.gen.inc.php");

return array(
	'js' => array('//templates/webkit/javascripts/jqtouch/jqtouch.js', '//templates/webkit/javascripts/db.init.js', '//templates/webkit/javascripts/ga.init.js', '//calendar/templates/webkit/javascripts/calendar.init.js', '//people/templates/webkit/javascripts/people.init.js', '//map/templates/webkit/javascripts/map.init.js'),
    'css' => array('//templates/webkit/javascripts/jqtouch/jqtouch.css', '//themes/'.$theme.'/webkit/stylesheets/theme.css', '//themes/'.$theme.'/webkit/stylesheets/extra.css'),
);