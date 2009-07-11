<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "Page.php";
require "page_tools.php";
require "counter.php";

$phone = Page::classify_phone();
$page = Page::factory($phone);
$prefix = $page->requirePrefix();

//find which page is being requested
preg_match('/\/((\w|\-)+)\/[^\/]*?$/', $_SERVER['REQUEST_URI'], $match);
$content = $match[1];

PageViews::increment($content);

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
