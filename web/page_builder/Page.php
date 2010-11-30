<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class Page {

  protected $title;
  protected $header;
  protected $stylesheets = array();
  protected $javascripts = array();
  protected $container;
  protected $footer="";
  protected $footer_script = NULL;
  protected $compressed_mode = True;

  protected $phone;
  protected $parts;

  public static $phoneType;

  protected $aquire_mode = False;
  protected $aquired = False;

  public static function factory($phone_type) {
    $type = $phone_type . "Page";
    self::$phoneType = $phone_type;
    return new $type();
  }

  public function cache() {
    header("Cache-Control: max-age=86400");
    return $this;
  }

  public function title($title) {
    $this->title = $title;
    return $this;
  }

  public function header($header) {
    $this->header = $header;
    return $this;
  }

  public function add_stylesheet($stylesheet_name) {
    $this->stylesheets[] = $stylesheet_name;
    return $this;
  }

  public function add_javascript($js_name) {
    $this->javascripts[] = $js_name;
    return $this;
  }

  public function footer_script($script) {
    $this->footer_script = $script;
    return $this;
  }

  public function extra_footer($footer) {
    $this->footer = $footer . ' ';
    return $this;
  }

  protected $help_on = True;

  public function help_off() {
    $this->help_on = False;
    return $this;
  }

  public function content_begin() {
    if($this->aquire_mode) {
      throw new Exception("Content already begun");
    } elseif ($this->aquired) {
      throw new Exception("Content already set");
    } else {
      ob_start();
      $this->aquire_mode = True;
    }
  }

  public function content_end() {
    if($this->aquire_mode) {
      $this->content = ob_get_clean();
      $this->aquire_mode = False;
      $this->aquired = True;
    }
  }

  public function output($base = false) {
    require "../../config.gen.inc.php";

    foreach($this->varnames as $varname) {
      ${$varname} = $this->$varname;
    }
    $phone = $this->phone;
    $prefix = $this->phone;
    
    ob_start();
    require "../templates/$prefix/base.html";
    $uncompressed_html = ob_get_clean();

    // replace large chunks of spaces with a single space
    $compressed_html = preg_replace('/\s*?\n\s*/', "\n", $uncompressed_html);    $compressed_html = preg_replace('/( |\t)( |\t)*/', " ", $compressed_html);
    if($this->compressed_mode) {
       echo $compressed_html;
    } else {
      echo $uncompressed_html;
    }  
  }

  protected function draw_content() {
    //draw the main content of the page
    echo $this->content;
  }

}

class webkitPage extends Page {
  public function __construct() {
    $this->phone = "webkit";
	$this->varnames = array();
  }
}

class touchPage extends Page {

  protected $navbar_image;
  protected $breadcrumb_root = False;
  protected $breadcrumbs = array();
  protected $last_breadcrumb;
  protected $breadcrumb_links;
  protected $extra_onload = "scrollTo(0,1);";
  protected $onorientationchange;
  protected $raw_js = array();
  protected $scalable = "yes";
  protected $fixed = False;

  public function __construct() {
    $this->phone = "touch";
    $this->varnames= array(
       "title", "header", "navbar_image", "stylesheets", "javascripts", "breadcrumb_links",
       "home", "breadcrumbs", "last_breadcrumb", "help_on", "footer", "footer_script",
       "extra_onload", "onorientationchange", "raw_js", "scalable", "fixed"
    );
  }

  public function fixed() {
    $this->fixed = True;
    return $this;
  }

  public function not_scalable() {
    $this->scalable = "no";
    return $this;
  }

  public function navbar_image($navbar_image) {
    $this->navbar_image = $navbar_image;
    return $this;
  }

  public function breadcrumbs() {
    $this->breadcrumbs = func_get_args();
    $this->last_breadcrumb = array_pop($this->breadcrumbs);
    $this->breadcrumb_links = array();
    return $this;
  }

  public function breadcrumb_links() {
    $tmp = func_get_args();
    for($cnt = 0; $cnt < count($tmp); $cnt++) {
      $this->breadcrumb_links[$cnt] = $tmp[$cnt];
    }
    return $this;
  }

  public function breadcrumb_home() {
    $this->home = True;
    return $this;
  }

  public function extra_onload($js) {
    $this->extra_onload .= " $js";
    return $this;
  }

  public function onorientationchange($js) {
    $this->onorientationchange = $js;
    return $this;
  }

  public function add_inline_script($js) {
    $this->raw_js[] = $js;
    return $this;
  }
}

class notIPhonePage extends Page {

  protected $extra_links = array();
  protected $help_links = array();
  protected $bottom_nav_links = array();

  public function __construct() {
    $this->varnames = array(
       "header", "title", "stylesheets", "extra_links", 
       "help_on", "help_links", "bottom_nav_links",
       "width1", "height1"
    );
  }

  public function extra_link($href, $text, $class=NULL) {
    $this->extra_links[] = array("url" => $href, "text" => $text, "class" => $class);
    return $this;
  }

  public function help_link($href, $text, $class=NULL, $phone=NULL) {
    $this->help_links[] = array("url" => $href, "text" => $text, "class" => $class, "phone" => $phone);
    return $this;
  }

  public function nav_link($href, $text) {
    $this->bottom_nav_links[] = array("url" => $href, "text" => $text);
    return $this;
  }
}

class basicPage extends notIPhonePage {
  protected $width1 = "48";
  protected $height1 = "19";

  protected $phone = "basic";  
}

?>
