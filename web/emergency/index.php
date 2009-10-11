<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../lib/rss_services.php";

// the logic to implement the page begins here
require "../page_builder/page_header.php";
require_once "../../config.gen.inc.php";
require_once "data/data.inc.php";

$emergency_message = "Coming Soon: Emergency Updates"; 
$Emergency = new RSS();
$emergencies = $Emergency->get_feed($emergency_rss_feed);

if($emergencies === False) {
  $paragraphs = array('Emergency information is currently not available');
} else {
  foreach ($emergencies as $title => $emergency) {
        $text = explode("\n", $emergency['text']);
        $paragraphs = array();
	foreach($text as $paragraph) {
	  if($paragraph) {
	    $paragraphs[] = htmlentities($paragraph);
	  }
	}
        $article_c_timestamp = mktime($emergency['date']['hours'],$emergency['date']['minutes'],$emergency['date']['seconds'],$emergency['date']['mon'],$emergency['date']['mday'],$emergency['date']['year']);
        $article_f_timestamp = mktime($emergency['date']['hours'],$emergency['date']['minutes'],$emergency['date']['seconds'],$emergency['date']['mon'],$emergency['date']['mday']+2,$emergency['date']['year']);
        $current_timestamp = time();
        $date = short_date($emergency['date']);
  }

  // handle the case that an emergency RSS feed doesn't return data until emergency (like e2campus)
  if ($paragraphs == False) {
	$paragraphs = array("There is currently no emergency on campus.");
  }
}

if(isset($_REQUEST['contacts'])) {
  require "$prefix/contacts.html";
} else if (isset($_REQUEST['extra'])) {
  require "$prefix/extra.html";
} else if (isset($_REQUEST['residence'])) {
  require "$prefix/residence.html";
} else if (isset($_REQUEST['schools'])) {
  require "$prefix/schools.html";
} else {
  require "$prefix/index.html";
}

$page->output();

function contactsURL() {
  return "./?contacts=true";
}

function extraURL() {
  return "./?extra=true";
}

function schoolsURL() {
  return "./?schools=true";
}

function residencesURL() {
  return "./?residence=true";
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
