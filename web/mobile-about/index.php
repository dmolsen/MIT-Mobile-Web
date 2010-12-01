<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// libs
require_once "lib/textformat.lib.php";

// dynamic pages need to include dynamics scripts
switch($_REQUEST['page']) {

  // static cases
  case "requirements":
  case "new":
  case "credits":
  case "homescreen":
    require "templates/$prefix/{$_REQUEST['page']}.html";
	$page->output();
    break;

  // phone dependant cases
  case "about":
  default:
    require "templates/$prefix/about.html";
	$page->output();
}

?>
