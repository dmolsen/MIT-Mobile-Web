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

// dynamically find module init files so there's less configuration
function find_js_inits() {
	$base_dir = getcwd()."/../";
	$files = scandir($base_dir);
	foreach ($files as $file) {
		if (is_dir($base_dir.$file)) {
			$js_file = $base_dir.$file."/templates/webkit/javascripts/".$file.".init.js";
			if (file_exists($js_file)) {
				$js_a[] = "//".$file."/templates/webkit/javascripts/".$file.".init.js";
			}
		}
	}
	return $js_a;
}

// the standard JS and CSS files that need to be minified
$js_a = array('//templates/webkit/javascripts/jqtouch/jqtouch.js', '//templates/webkit/javascripts/db.init.js', '//templates/webkit/javascripts/ga.init.js', '//templates/webkit/javascripts/aria.init.js');
$css_a = array('//templates/webkit/javascripts/jqtouch/jqtouch.css', '//themes/'.$theme.'/webkit/stylesheets/theme.css', '//themes/'.$theme.'/webkit/stylesheets/extra.css');

// check min cache support, if set to true see if the cache file is created
if ($min_cache_support) {	
	
	// set-up the cache file path
	$timestamp = date('Ymd');
	$min_js_file = '../../cache/min_js_cache_'.$timestamp.'.txt';
	
	// check if cache file exists
	if (!file_exists($min_js_file)) {
		
		// if cache doesn't exist yet get the dynamic js files
		$dynamic_js_a = find_js_inits();
		
		// build the text array to be cached
		$js = "array(";
		foreach($dynamic_js_a as $dynamic_js) {
			$js .= "'".$dynamic_js."',";
		}
		$js .= ");";
		
		// write out the cache file
		$fp = fopen($min_js_file, 'w');
		fwrite($fp, "<?php \$dynamic_js_a = ".$js." ?>");
		fclose($fp);
		
	} else {		
		// if cache does exist simply include it and merge it with standard js includes
		include($min_js_file);	
	}
	
	$js_a = array_merge($js_a, $dynamic_js_a);
	
} else {
	
	// find the module JS files to include on the fly since cache is apparently not needed
	$js_a = array_merge($js_a, find_js_inits());
	
}

return array(
	'js' => $js_a,
    'css' => $css_a
);