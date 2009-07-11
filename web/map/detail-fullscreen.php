<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../page_builder/page_header.php";

$selectvalue = $_REQUEST['selectvalues'];
$bbox = split(',', $_REQUEST['bbox']);
$minx = $bbox[0];
$miny = $bbox[1];
$maxx = $bbox[2];
$maxy = $bbox[3];

$bbox = split(',', $_REQUEST['bboxSelect']);
$minxSelect = $bbox[0];
$minySelect = $bbox[1];
$maxxSelect = $bbox[2];
$maxySelect = $bbox[3];

$field = $_REQUEST['selectfield'];
$layer = $_REQUEST['selectlayer'];
$layers = $_REQUEST['layers'];

require "$prefix/detail-fullscreen.html";

?>
