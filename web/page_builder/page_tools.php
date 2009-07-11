<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


abstract class DrillDownList {
  
  protected $lower;
  protected $upper;
  protected $keys_or_values;

  public function __construct($limits_str, $type='value') {
    $limits = explode('-', strtolower($limits_str));
    $this->lower = $limits[0];
    $this->upper = $limits[1];
    $this->keys_or_values = !($type == 'value');
  }

  public function get_list($items) { 
    $sublist = array();
    foreach($items as $key => $value) {
      $filtered = $this->keys_or_values ? $key : $value; 
      if($this->isBetween($filtered)) {
	  $sublist[$key] = $value;
      } 
    }
    return $sublist;
  }

  protected function isBetween($target) {
    $target = strtolower($target);
    if($this->upper) {
      return $this->isBetween2($target);
    } else {
      return preg_match("/^{$this->lower}/", $target);
    }
  }

  abstract protected function isBetween2($target);
}

class DrillAlphabeta extends DrillDownList {
  protected function isBetween2($target) {
    return (($this->lower < $target) && ($target < $this->upper))
      || preg_match("/^{$this->lower}/", $target)
      || preg_match("/^{$this->upper}/", $target);
  }
}

class DrillNumeralAlpha extends DrillDownList {
  protected function isBetween2($target) {
    preg_match("/^([a-z]*)(\d+)/",$this->lower, $bottom_match);
    preg_match("/^([a-z]*)(\d+)/",$this->upper, $top_match);
    preg_match("/^([a-z]*)(\d+)/",$target, $target_match);

    if($target_match[1] > $bottom_match[1] &&
       $target_match[1] < $bottom_match[1]) {
      return true;
    }

    if($bottom_match[1] == $top_match[1]) {
      if($target_match[1] != $bottom_match[1]) {
        return false;
      } else {
        return ((int) $target_match[2] >= (int) $bottom_match[2])
	  && ((int) $target_match[2] <= (int) $top_match[2]);
      }
    }
      
    if($target_match[1] == $bottom_match[1] &&
       (int)$target_match[2] >= (int)$bottom_match[2]) {
      return true;
    }

    if($target_match[1] == $top_match[1] &&
       (int)$target_match[2] <= (int)$top_match[2]) {
      return true;
    }
  }
}

class Pager {
  private $items;
  private $start; 
  private $end;
  private $limit;

  function __construct($phone, $items, $start=0) {
    $this->items = $items;
    if($phone == 'ip') {
      //no paging for the iPhone
      $this->limit = NULL;
      $this->start = 0;
      $this->end = count($items);
    } else {
      // 20 elements for smart phones, 10 elements for feature phones
      $limits = array("sp" => 20, "fp" => 10);
      $this->limit = $limits[$phone];
      $this->start = $start;
      $this->end = min($this->limit + $start, count($items));
    }
  }
  
  public function last() {
    return $this->end;
  }

  public function items() {
    $length = $this->end - $this->start;
    return array_slice($this->items, $this->start, $length);
  }

  public function prev_id() {
    if($this->start == 0) {
      return NULL;
    }

    if($this->limit === NULL) {
      return 0;
    }

    $prev_id = $this->start - $this->limit;

    return ($prev_id >= 0) ? $prev_id : 0;
  }
    
  public function next_id() {
    if($this->end == count($this->items)) {
      return NULL;
    } else {
      return $this->end;
    }
  }

  public function next_html($url, $params, $id_name) {
    if($this->next_id() === NULL) {
      return '';
    }

    $params[$id_name] = $this->next_id();
    $url .= '?' . http_build_query($params);
    
    return '<a href="' . $url . '">Next &gt;</a>';
  }

  public function prev_html($url, $params, $id_name) {
    if($this->prev_id() === NULL) {
      return '';
    }

    $params[$id_name] = $this->prev_id();
    $url .= '?' . http_build_query($params);
    
    return '<a href="' . $url . '">&lt; Prev</a>';
  }

  public function prev_next_html($url, $params, $id_name) {
    $next = $this->next_html($url, $params, $id_name);
    $prev = $this->prev_html($url, $params, $id_name);
    $middle = ($next && $prev) ? "&nbsp;|&nbsp;" : "";
    return $prev . $middle . $next;
  }
}  

class Tabs {
  
  private $url;
  private $tabs;
  private $active;
  private $param;
  
  public function __construct($url, $param, $tabs) {
    $this->url = $url;
    $this->tabs = $tabs;
    $this->active = $_REQUEST[$param];
    $this->param = $param;
  }

  public function hide($tab) {
    $index = array_search($tab, $this->tabs);
    $this->tabs = array_merge(
      array_slice($this->tabs, 0, $index),
      array_slice($this->tabs, $index+1)
    );
  }

  public function active() {
    if($this->active) {
      return $this->active;
    } else {
      return $this->tabs[0];
    }
  }

  public function html() {
    $html = array();
    foreach($this->tabs as $index => $tab) {
      $url = $this->url . "&" . $this->param . "=" .urlencode($tab);
      if($tab == $this->active()) {
	  $html[] = '<span class="active">' . $tab . '</span>';
      } else {
	  $html[] = "<a href=\"$url\">$tab</a>";
      }
    }
    return implode("&nbsp;|&nbsp;", $html);
  }
}

function short_date($date) {
  $minute = $date['minute'];
  $minute = $minute < 10 ? '0' . $minute : (string) $minute;
  return "{$date['month']}/{$date['day']} {$date['hour']}:$minute";
}


class ResultsContent {
  private $form;

  private $prefix;
  private $phone;

  // the KEYWORD which appears in the pages title
  private $title;

  // the file the contains the formating of the output list
  private $template;
 
  // the module where the list template can be found
  private $module;

  // extra parameters needed for the next and previous arrows
  private $extra_params;

  public function __construct($template, $module, $prefix, $phone, $extra_params = array()) {
    $this->template = $template;
    $this->module = $module;
    $this->prefix = $prefix;
    $this->phone = $phone;
    $this->extra_params = $extra_params;
    $this->form = new StandardForm($prefix);
  }
   
  public function set_form(Form $form) {
    $this->form = $form;
    return $this;
  }

  public function output($results) {
  
    $total = count($results);

    //truncate results and determine if a next page is needed
    $start = $_REQUEST["start"] ? (int)$_REQUEST["start"] : 0;
    $pager = new Pager($this->phone, $results, $start);
    $results = $pager->items();

    $search_terms = $_REQUEST['filter'];
    $params = array_merge(array("filter" => $search_terms), $this->extra_params);
    $arrows = $pager->prev_next_html($_SERVER['SCRIPT_NAME'], $params, "start");

    $start++;
    $end = $pager->last();


    require "../{$this->prefix}/search_results.html";
    return $this->page;
  }
}
 
abstract class Form {

  protected $prefix;

  public function __construct($prefix) {
    $this->prefix = $prefix;
  }

  abstract public function out();
}

class StandardForm extends Form {

  public function out($total=NULL) {
    require "../{$this->prefix}/form.html";
  }
}
     
define('MAX_TEXT', 80);

function summary_string($text) {

  //check if the last character or
  //the character after last is not a word character
  if( preg_match('/\W/', substr($text, MAX_TEXT-1, 2)) ) {
    $dots = False;
  } else {
    $dots = True;
  }

  $text = htmlentities(substr($text, 0, MAX_TEXT));
  if($dots) {
    $text .= '...';
  }

  //this hack fixes some strange encoding problem
  return str_replace('&Acirc;', '', $text);
}

function is_long_string($text) {
  $temp = trim($text);
  return strlen($temp) > MAX_TEXT;
}

?>