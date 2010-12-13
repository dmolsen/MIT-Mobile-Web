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

$js_a = array('//templates/webkit/javascripts/jqtouch/jqtouch.js', '//templates/webkit/javascripts/db.init.js', '//templates/webkit/javascripts/ga.init.js', '//templates/webkit/javascripts/aria.init.js');
$css_a = array('//templates/webkit/javascripts/jqtouch/jqtouch.css', '//themes/'.$theme.'/webkit/stylesheets/theme.css', '//themes/'.$theme.'/webkit/stylesheets/extra.css');

// dynamically load module init files so there's less configuration
$base_dir = getcwd();
$base_dir = str_replace("min","",$base_dir);
$files = scandir($base_dir);
foreach ($files as $file) {
	if (is_dir($base_dir.$file)) {
		$js_file = $base_dir.$file."/templates/webkit/javascripts/".$file.".init.js";
		if (file_exists($js_file)) {
			$js_a[] = "//".$file."/templates/webkit/javascripts/".$file.".init.js";
		}
	}
}

return array(
	'js' => $js_a,
    'css' => $css_a
);