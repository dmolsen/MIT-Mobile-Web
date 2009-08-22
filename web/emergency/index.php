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
  i("3042930111", "Campus Operator/Information"),
  i("3042936997", "Carruth Center for Counseling and Psychological Services"),
  i("3042936700", "Disability Services"),
  i("3042933792", "Environment, Health & Safety"),
  i("3042935590", "Faculty-Staff Assistance Program"),
  i("3042936924", "Health Sciences Safety Office"),
  i("8009880096", "Parents Club Hotline"),
  i("3042934357", "Physical Plant"),
  i("3042932311", "Student Health Services"),
  i("3042934444", "Telephone Service Problems"),  
  i("8009880096", "WVU Emergency Line"),
  i("3042930111", "WVU General Information")
);

$extra = array(
  i("3042932121", "Admissions and Records"),
  i("3042935496", "ADA Office"),
  i("3042934731", "Alumni Association"),
  i("8009884263", "Athletic Ticket Office"),
  i("3042937029", "Center for Black Culture and Research"),
  i("3042937469", "Creative Arts Center Box Office"),
  i("3042936700", "Disability Services"),
  i("3042935691", "Extension & Public Service"),
  i("3042935242", "Financial Aid"),
  i("3042934491", "Housing & Residence Life"),
  i("3042934444", "Mountain Line Bus Service"), 
  i("3042937469", "Mountainlair Box Office"), 
  i("8009880096", "New Student Orientation"),
  i("3042930111", "News & Information Services"),
  i("3042935502", "Parking Enforcement"),
  i("3042935011", "PRT Maintenance"),
  i("3042935531", "President's Office"),
  i("3042934126", "Scholars Office"),
  i("3042935496", "Social Justice"),
  i("3042934006", "Student Accounts"),
  i("3042935811", "Student Affairs"),
  i("3042937529", "Student Recreation Center"),
  i("3042938028", "Trademark Licensing"),
  i("3042933489", "Visitors Center/Tour Info"),
  i("8002255982", "West Virginia Tourism Info"),
  i("3042844000", "WVU Foundation")
);

$residence = array(
  i("3042932840", "Arnold Hall"),
  i("3042932010", "Boreman North"),
  i("3042932010", "Boreman South"),
  i("3042936798", "College Park, The Ridge"),
  i("3042934601", "Dadisman Hall"),
  i("3042932813", "Bennett Tower"),
  i("3042932814", "Braxton Tower"),
  i("3042932814", "Brooke Tower"),
  i("3042932813", "Lyon Tower"),
  i("3042937050", "Fieldcrest Hall"),  
  i("3042932010", "International House"),
  i("3042936170", "Lincoln Hall"),
  i("3042933116", "Pierpont Apartments"),
  i("3042938149", "Stalnaker"),
  i("3042933123", "Summit")
);

$schools = array(
  i("3042932395", "Davis College of Agriculture, Forestry, & Consumer Sciences"),
  i("3042934661", "Eberly College of Arts & Sciences"),
  i("3042934092", "Business & Economics"),
  i("3042934841", "Creative Arts"),
  i("3042932521", "School of Dentistry"),
  i("3042935695", "Engineering & Mineral Resources"),
  i("3042932100", "Honors College"),
  i("3042935703", "Human Resources & Education"),
  i("3042933505", "Perley Isaac Reed School of Journalism"),
  i("3042935304", "College of Law"),  
  i("3042936607", "School of Medicine"),
  i("3042934831", "School of Nursing"),
  i("3042935101", "School of Pharmacy"),
  i("3042933295", "College of Physical Activity & Sports Sciences"),
  i("3047886800", "Potomac State College of WVU"),
  i("2034423071", "WVU Institute of Technology")
);


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
