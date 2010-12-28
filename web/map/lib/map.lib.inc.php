<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

function detailURL($id,$latitude,$longitude,$parent=nil) {
  return "/map/detail.php?loc=".$id."&lat=".$latitude."&long=".$longitude."&maptype=roadmap";
}

function drillURL($drilldown, $name=NULL) {
  $category = $_REQUEST['category'];
  $url = "/map/drilldown.php?category=".$category."&drilldown=".$drilldown;
  if ($name) {
    $url .= "&desc=" . urlencode($name);
  }
  return $url;
}

function categoryURL($category=NULL) {
  $category = $category ? $category : $_REQUEST['category'];
  return "/map/category.php?category=$category";
}

function searchURL() {
  return "/map/search.php";
}

?>