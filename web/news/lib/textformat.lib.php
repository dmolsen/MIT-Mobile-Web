<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

function detailURL($title,$src) {
  return "/news/detail.php?title=".urlencode($title)."&src=$src";
}

function is_long_text($item) {
  return is_long_string($item);
}

function summary($item) {
  return summary_string(str_replace(' [...]','...',(str_replace('Read more ...','',$item))));
}

function full($item) {
  return $item;
}

function article_date($rss_time) {
  return date('M. jS @ g:ia',strtotime($rss_time));
}

?>