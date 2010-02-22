<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../page_builder/page_header.php";

//various copy includes
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

require "lib/calendar_lib.php";

require "templates/$prefix/categorys.html";
$page->output();

?>
