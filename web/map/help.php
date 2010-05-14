<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../config.gen.inc.php";

$header = $inst_name." Campus Map";
$module = "map";

$help = array(
  'Find places on the '.$inst_name.' campus in two ways:',

  '1. <strong>Search</strong> by building number or name, or for specific facilities by name or keyword<br />' .
  'Examples: &quot;university&quot;, &quot;soccer&quot;, etc.',

  '2. <strong>Browse</strong> by any of the categories on the Campus Map homepage',

  'For each building, you&apos;ll be shown its name, street address, map and any extra information we have related to it (e.g. library hours).', 

  'Note that some buildings are located away from streets; for those buildings, the street address shown is usually the main pedestrian access from the street.',

);

require "../page_builder/help.php";

?>
