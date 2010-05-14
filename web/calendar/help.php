<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

//various copy includes
require_once "../../config.gen.inc.php";

$header = $inst_name." Events Calendar";
$module = "calendar";

$help = array(
  'Find out what\'s going on around campus. You can find events in two ways:',

  '<ol><li><strong>Browse</strong> by any category shown on the Calendar homepage</li>'.

  '<li><strong>Search</strong> by keyword &amp; timeframe</li></ol>',

  'We hope you find what you\'re looking for.');

require "../page_builder/help.php";

?>
