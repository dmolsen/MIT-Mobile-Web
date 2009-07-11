<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


function double_digit($hour) {
  $hour = $hour % 24;
  if($hour < 10) {
    return '0' . $hour;
  } else {
    return (string) $hour;
  }
}

function routeURL($route) {
  return "times.php?route=" . $route->encodeName();
}

function Letter($number) {
  return chr($number + ord('A'));
}

?>