<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "Page.php";
require_once "detection.php";
require_once "adapter.php";
require_once "page_tools.php";
require_once "counter.php";

$prefix = Device::templates();
$page = Page::factory($prefix);

// check to see if this is a deep link, if so redirect
// must be a webkit classified browser and missing the interal request [ir] var
// ir var is defined in jqtouch for GETs and as a hidden var in forms
if (($prefix == 'webkit') && ((!$_REQUEST['ir']) || ($_REQUEST['ir'] == ''))) {
	header("location:/?redirect=".$_SERVER['REQUEST_URI']);
}

// record stats for the section in the database
$section = Page::getSection($_SERVER['REQUEST_URI']);
PageViews::increment($section);

class DataServerException extends Exception {
}

// use php default error handler for the dev version of the web site
// unccmment the line below to use the custom exception handler
// set_exception_handler("exception_handler");

function exception_handler($exception) {

  if(is_a($exception, "DataServerException")) {
    $error_query = "code=data&url=" . urlencode($_SERVER['REQUEST_URI']);
  } else {
    $error_query = "code=internal";
  }
  $error_url = "../error-page/?{$error_query}";

  $recipients = array(
    "zootsuitbrian@gmail.com",
  );

  $recipient_str = implode(", ", $recipients);

  // a text representation of the exception
  ob_start();
  var_dump($exception);
  $text = ob_get_contents();
  ob_end_clean();

  if(!Page::is_spider()) {
    mail(
      $recipient_str, 
      "mobile web page experiencing problems",
      "the following url is throwing exceptions: http://mobi.mit.edu{$_SERVER['REQUEST_URI']}\n" .
      "Exception:\n" . 
      "$text\n" .
      "The User-Agent: \"{$_SERVER['HTTP_USER_AGENT']}\"\n" .
      "The referer URL: \"{$_SERVER['HTTP_REFERER']}\""
    );
  }

  header("Location: {$error_url}");
  die(0);
}


?>
