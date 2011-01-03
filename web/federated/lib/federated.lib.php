<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// look through all the directories to see which ones have the federated.php file
function findFederated() {
	$federated_a = array();
	$base_dir = getcwd()."/../";
	$files = scandir($base_dir);
	foreach ($files as $file) {
		if (is_dir($base_dir.$file)) {			
			$filepath =  $base_dir.$file."/federated.php";
			if (file_exists($filepath)) {
				$federated_a[] = $filepath;
			}
		}
	}
	return $federated_a;
}

// try to find the cache file. if it doesn't exist create it.
function getCacheFederated() {
	// set-up the cache file path
	$timestamp = date('Ymd');
	$fed_cache_file = '../../cache/fed_cache_'.$timestamp.'.txt';

	// check if cache file exists
	if (!file_exists($fed_cache_file)) {

		// if cache doesn't exist yet get the dynamic js files
		$federated_a = findFederated();

		// build the text array to be cached
		$federated_txt = "array(";
		foreach($federated_a as $federated) {
			$federated_txt .= "'".$federated."',";
		}
		$federated_txt .= ");";

		// write out the cache file
		$fp = fopen($fed_cache_file, 'w');
		fwrite($fp, "<?php \$federated_a = ".$federated_txt." ?>");
		fclose($fp);

	} else {

		// if cache does exist simply include it and merge it with standard js includes
		include($fed_cache_file);

	}
	
	return $federated_a;
}

?>