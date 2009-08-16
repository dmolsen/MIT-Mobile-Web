<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../lib/db.php";
require_once "../../lib/rss_services.php";
require_once "../page_builder/page_header.php";
require_once "../../config.gen.inc.php";

if ($_REQUEST['streams'] == "true") {
	$streams = array();
	$streams["WIFI (265kbps)"] = "http://157.182.129.241:554/u92Live-256k.m3u";
	$streams["3G (128kbps)"] = "http://157.182.129.241:554/u92Live-128k.m3u";
	$streams["EDGE (32kbps mono)"] = "http://157.182.129.241:554/u92Live-32k-mono.m3u";
	
	require "$prefix/streams.html";
}
else {
	$db = db::$connection;
	$stmt_1 = $db->prepare("SELECT * FROM RadioShowTimes WHERE day = ? and start <= CAST(? AS INT) and end >= CAST(? AS INT)");
	if (db::$use_sqlite) {
		$stmt_1->execute(array(date('D'),date('G'),date('G')));
	}
	else {
		$stmt_1->bind_param('sii',date('D'),date('G'),date('G'));
		$stmt_1->execute();
	}
	$showtime = $stmt_1->fetchAll();

	$stmt_2 = $db->prepare("SELECT * FROM RadioShows WHERE id = CAST(? AS INT)");
	if (db::$use_sqlite) {
		$stmt_2->execute(array($showtime[0]['show']));
	}
	else {
		$stmt_2->bind_param('i',$showtime[0]['show']);
		$stmt_2->execute();
	}
	$show = $stmt_2->fetchAll();
        
	$showname = $show[0]['name'];
	$showid = $show[0]['id'];

	$News = new RSS();
	$items = $News->get_feed('http://157.182.32.8/u92/u92.xml');
	
	# name, link, show for sp & ip?, use external class?
	$links = array(array("List of shows","shows.php",true,false),array("U92 on iTunes U","http://deimos.apple.com/WebObjects/Core.woa/Browse/wvu.edu.1353247216",false,true),array("U92's full web site","http://u92.wvu.edu/",true,true));
	
	require "$prefix/index.html";
}

$page->cache();
$page->output();

function detailURL($title,$src) {
  return "detail.php?title=$title&src=$src";
}

function summary($item) {
  return summary_string(str_replace('Read more ...','',$item['text']));
}
    
?>
