<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

$main = array(
  i("3042932677", "Campus Police"),
  i("3042936924", "Health Sciences Safety Office"),
  i("8009880096", "WVU Emergency Line"),
  i("3042930111", "WVU General Information")
);

$others = array(
  i("3042933136", "Campus Police"),
  i("3042936997", "Carruth Center for Counseling and Psychological Services"),
  i("3042936700", "Disability Services"),
  i("3042933792", "Environment, Health & Safety"),
  i("3042935590", "Faculty-Staff Assistance Program"),
  i("3042936924", "Health Sciences Safety Office"),
  i("8009880096", "Parents Club Hotline"),
  i("3042934357", "Physical Plant"),
  i("3042932311", "Student Health Service"),
  i("3042934444", "Telephone Service Problems"),  
  i("8009880096", "WVU Emergency Line"),
  i("3042930111", "WVU General Information")
);

require_once "../../lib/rss_services.php";

$emergency_message = "Coming Soon: Emergency Updates"; 
$Emergency = new Emergency();
$emergencies = $Emergency->get_feed();

if($emergencies === False) {
  $paragraphs = array('Emergency information is currently not available');
} else {
  foreach ($emergencies as $title => $emergency) {
	$text = explode("\n", $emergency[$title]['text']);
	$paragraphs = array();
	foreach($text as $paragraph) {
	  if($paragraph) {
	    $paragraphs[] = htmlentities($paragraph);
	  }
	}
  }

  // handle the case that an emergency RSS feed doesn't return data until emergency (like e2campus)
  if ($paragraphs == False) {
	$paragraphs = array("There is currently no emergency on campus.");
  }
}

// the logic to implement the page begins here
require "../page_builder/page_header.php";
require_once "../../config.inc.php";

if(isset($_REQUEST['contacts'])) {
  require "$prefix/contacts.html";
} else {
  require "$prefix/index.html";
}

$page->output();

function contactsURL() {
  return "./?contacts=true";
}

class EmergencyItem {
  private $number;
  private $label;
  private $message;

  // letters on a phone key-pad
  private static $letters = array(
    "A-C" => 2,
    "D-F" => 3, 
    "G-I" => 4,
    "J-K" => 5,
    "M-O" => 6,
    "P-S" => 7,
    "T-V" => 8,
    "W-Z" => 9
  );

  public function __construct($number, $label, $message) {
    $this->number = $number;
    $this->label = $label;
    $this->message = $message;
  }
  
  public function call_number() {
    $init = $this->number;
    foreach(self::$letters as $letters => $digit) {
      $init = preg_replace("/[$letters]/", $digit, $init);
    }
    return $init;
  }

  public function number_text() {
    return substr($this->number, 0, 3) . "." . substr($this->number, 3, 3) . "." . substr($this->number, 6, 4);
  }

  public function label() {
    return htmlentities($this->label);
  }

  public function message_text() {
    if($this->message) {
      return htmlentities($this->message . ": ");
    } else {
      return "";
    }
  }
}

function i($number, $label, $message=NULL) {
  return new EmergencyItem($number, $label, $message);
}



?>
