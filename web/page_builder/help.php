<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../page_builder/Page.php";
require_once "../page_builder/counter.php";
require_once "../page_builder/detection.php";
require_once "../../config.gen.inc.php";

$prefix = Device::templates();
$page = Page::factory($prefix);

require "../templates/$prefix/help.html";

# including this section screws w/ jQTouch
if ($prefix != 'webkit') {
	$page->cache();
	$page->output();
}

?>
