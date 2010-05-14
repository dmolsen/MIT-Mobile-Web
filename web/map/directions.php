<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

$dimensions = array(
  "iphone" => array(280, 238),
  "touch" => array(220, 220)
);

$width = $dimensions[$phone][0];
$height = $dimensions[$phone][1];

$google =<<<HTML
<p>To find WVU using Google Maps, use the address "<a href="http://maps.google.com/maps?f=q&q=1+Waterfront+Pl,+Morgantown+WV+26505">1 Waterfront Pl, Morgantown WV 26505</a>" as your reference point for general directions to WVU. This is our Visitors Resource Center.
</p>
HTML;

$google_extra =<<<EXTRA
<li class="arrow"><a href="http://maps.google.com/maps?f=q&q=1+Waterfront+Pl,+Morgantown+WV+26505" class="external">WVU on Google Maps</a></li>
EXTRA;

$car =<<<HTML
<p>At the intersection of I-79 and I-68, <span class="caps">WVU</span> is approximately 70 miles south of Pittsburgh or 200 miles northwest of Washington, DC. The Visitors Resource Center is accessible from Exit 1 of Interstate 68 (University Ave. exit). If you exit from I-68 West turn left at the end of the exit ramp on to Route 119 North; go straight for three stoplights. If you exit from I-79 onto I-68 East turn left at the light on Route 119 North; go straight for three stoplights. At the fourth traffic light turn left into One Waterfront Place. You will see a large blue sign labeled Visitors Resource Center.</p>
<p>As you turn left, make a quick right into the parking garage. You will receive a parking ticket that you will need to bring inside with you to have validated for your time at the Visitors Resource Center. The Visitors Resource Center is located on the first floor.</p>
HTML;

$air =<<<HTML
<p>The closest airports are the <a href="http://www.morgantownairport.com/">Morgantown Airport</a> and the <a href="http://www.pitairport.com/redirect.jsp">Pittsburgh International Airport</a>.  The <a href="http://www.busride.org/">Gray Line Mountain Line Bus</a> runs a shuttle from Pittsburgh to Morgantown daily.</p>
HTML;

$train =<<<HTML
<p>The closest <a href="http://www.amtrak.com/servlet/ContentServer?pagename=Amtrak/HomePage">Amtrak</a> station is in Greensburg, Pa., located 51.5 miles from Morgantown.
HTML;

$directions = array(
  "google" => dirs($google, "Using Google Maps", "Google")->google($google_extra),
  "car" => dirs($car, "By Car", "By Car"),
  "air" => dirs($air, "By Air", "By Air"),
  "train" => dirs($train, "By Train", "By Train"),
);


class DirectionPage {
  public $header;
  public $html;
  public $breadcrumb;
  public $google_html;
  public $link_text;

  public function __construct($html, $link_text, $breadcrumb) {
    $this->html = $html;
    $this->link_text = $link_text;
    $this->breadcrumb = $breadcrumb;
    $this->header = $this->link_text;
  }

  public function heading($header) {
    $this->header = $header;
    return $this;
  }

  public function google($google_html) {
    $this->google_html = $google_html;
    return $this;
  }

  public function short_link($link_text) {
    if(Page::$phoneType != "ip") {
      $this->link_text = $link_text;
    }
    return $this;
  }
}

function dirs($html, $link_text, $breadcrumb) {
  return new DirectionPage($html, $link_text, $breadcrumb);
}

function directionsURL($link) {
  return "/map/directions.php?page=$link";
}

if($_REQUEST['page']) {
  $info = $directions[ $_REQUEST['page'] ];
  require "templates/$prefix/direction.html";
} else {
  require "templates/$prefix/directions.html";
}

$page->output();

?>
