<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

//various copy includes
require_once "../../config.gen.inc.php";

$header = $inst_name." on YouTube";
$module = "youtube";

$help = array(
  'The WVU on YouTube mobile site requires a phone that supports RTSP streaming and is on a service carrier which permits streaming videos. YouTube for mobile is a data-intensive application. You should consider getting a data plan before watching videos on YouTube from your mobile phone.');

require "../page_builder/help.php";

?>
