<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../config.gen.inc.php";

$header = $inst_name." News";
$module = "News";

$help = array(
    'Get the latest news items regarding events affecting the '.$inst_name.' community.',
);

require "../page_builder/help.php";

?>
