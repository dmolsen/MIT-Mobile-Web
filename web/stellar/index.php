<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../page_builder/page_header.php";
require_once "stellar_lib.php";

require "$prefix/index.html";

$page->cache();
$page->output();
    
?>
