<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

function contactsURL() {
  return "/emergency/?contacts=true";
}

function extraURL() {
  return "/emergency/?extra=true";
}

function schoolsURL() {
  return "/emergency/?schools=true";
}

function residencesURL() {
  return "/emergency/?residence=true";
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