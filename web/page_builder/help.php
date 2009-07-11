<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/Page.php";
$phone = Page::classify_phone();
$page = Page::factory($phone);
$prefix = $page->requirePrefix();



require "../$prefix/help.html";

$page->cache();
$page->output();

?>
