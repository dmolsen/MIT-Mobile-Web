<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../lib/db.php";
require_once "../page_builder/page_header.php";
require_once "../../config.gen.inc.php";

if ((int)$_REQUEST['id'] != 0) {
	$db = db::$connection;
	$stmt_1 = $db->prepare("SELECT * FROM RadioShows WHERE id = CAST(? AS INT)");
	if (db::$use_sqlite) {
		$stmt_1->execute(array((int)$_REQUEST['id']));
	}
	else {
		$stmt_1->bind_param('i',(int)$_REQUEST['id']);
		$stmt_1->execute();
	}
	$show = $stmt_1->fetchAll();
        
	$showname = $show[0]['name'];
	$showid = $show[0]['id'];
	
	$stmt_2 = $db->prepare("SELECT * FROM RadioShowTimes WHERE show = CAST(? AS INT) ORDER BY day");
	if (db::$use_sqlite) {
		$stmt_2->execute(array($showid));
	}
	else {
		$stmt_2->bind_param('i',$showid);
		$stmt_2->execute();
	}
	$showtimes = $stmt_2->fetchAll();
	
	require "templates/$prefix/detail.html";
}
else {
	$db = db::$connection;
	$stmt_1 = $db->prepare("SELECT * FROM RadioShows ORDER BY name");
	$stmt_1->execute();
	$shows = $stmt_1->fetchAll();
    	
	require "templates/$prefix/list.html";
}

$page->cache();
$page->output();

function showURL($id) {
  return "show.php?id=$id";
}

function timestamp($time) {
        $time = (int)$time;
	if ($time == 24) {
		$hour = 12;
		$ampm = 'am';
	}
	else if ($time == 12) {
		$hour = 12;
		$ampm = 'pm';
	}
	else if ($time > 12) {
		$hour = $time - 12;
		$ampm = 'pm';
	}
	else {
		$hour = $time;
		$ampm = 'am';
	}
	return $hour.':00'.$ampm;
}

?>
