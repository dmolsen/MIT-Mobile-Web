<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


function coursesURL($which) {
  return "courses.php?which=$which";
}

function courseURL($id, $which) {
  return "course.php?id=$id&back=$which";
}

function longID($class) {
  return str_replace(' / ', '/', $class['name']);
}

function longerID($class) {
  return $class['name'];
}

function className($class) {
  return $class['title'];
}

function detailURL($class, $self) {
  return "detail.php?id={$class['masterId']}&back=" . urlencode($self);
}

function name($course) {
  return htmlentities($course["name"]);
}

function idName($id, $course) {
  $prefix = $course['is_course'] ? "Course " : "";
  return $prefix . $id;
}

?>
