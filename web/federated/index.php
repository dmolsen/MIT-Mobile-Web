<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// require libs
require_once "lib/federated.lib.php";

$filter = $_REQUEST['filter'];

$html = '';

if ($filter != '') {
	
	if ($fed_cache_support) {			
		// check fed cache support, if set to true see if the cache file is created
		$federated_a = getCacheFederated();
	} else {
		// find the fed files to include on the fly since cache is apparently not needed
		$federated_a = findFederated();
	}
	
	// go through each appropriate file and include it
	foreach ($federated_a as $federated) {
		include($federated);
	}
	
}

require "templates/$prefix/index.html";
$page->output();

?>