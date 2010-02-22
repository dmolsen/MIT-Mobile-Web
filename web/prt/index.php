<?php
/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require "../page_builder/page_header.php";
require_once "../../lib/db.php";
require_once "../map/lib/map.lib.inc.php";

//various copy includes
require_once "../../config.gen.inc.php";

$holiday = false;

$data = file_get_contents('http://prtstatus.sitespace.wvu.edu/cache.php?mobi=true');
preg_match("/<status>(.*?)<\/status>/i",$data,$matches);
$status = $matches[1];
preg_match("/<message>(.*?)<\/message>/i",$data,$matches);
$message = $matches[1];
preg_match("/<timestamp>(.*?)<\/timestamp>/i",$data,$matches);
$timestamp = $matches[1];

$db = db::$connection;
$stmt = $db->prepare("SELECT * FROM Buildings WHERE type = 'PRT Station' GROUP BY name ORDER BY name ASC");
$stmt->execute();
$places = $stmt->fetchAll();

require "$prefix/index.html";

$page->help_off();
$page->output();

?>
