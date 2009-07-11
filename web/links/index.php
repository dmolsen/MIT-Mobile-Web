<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require_once "../page_builder/page_header.php";

class Links {
  public static $links = array(
    'MBTA ("The T": Bus & Subway)' => 'www.mbta.com',
    'Zipcar' => 'www.zipcar.com',
    'MIT Technology Review' => 'mobile.technologyreview.com',
    'Fidelity NetBenefits - Staff & Faculty 401(k)' => 'www.fi-w.com/fiw/NBLogin',
  );
}

$links = array();
foreach(Links::$links as $name => $link) {
  $links[] = array(
    "name" => htmlentities($name),
    "link" => $link,
  );
}

require "$prefix/index.html";

$page->cache();
$page->output();
    
?>
