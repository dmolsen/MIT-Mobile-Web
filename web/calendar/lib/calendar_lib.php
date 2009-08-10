<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


function day_info($time, $offset=0) {
  $time += $offset * 24 * 60 * 60;
  return array(
    "weekday"       => date('l', $time),
    "month"         => date('F', $time),
    "month_3Let"    => date('M', $time),
    "day_num"       => date('j', $time),
    "year"          => date('Y', $time),
    "month_num"     => date('m', $time),
    "day_3Let"      => date('D', $time),
    "day_num_2dig"  => date('d', $time),
    "date"          => date('Y/m/d', $time),
    "gdate"         => date('Y-m-d', $time),
    "time"          => strtotime(date("Y-m-d 12:00:00", $time))
  );
}

class SearchOptions {
  private static $options = array(
    array("phrase" => "in the next 7 days",   "offset" => 7),
    array("phrase" => "in the next 15 days",  "offset" => 15),
    array("phrase" => "in the next 30 days",  "offset" => 30),
    array("phrase" => "this school term",     "offset" => "term"),
    array("phrase" => "this school year",     "offset" => "year")
  );

  public static function get_options($selected = 0) {
    $out_options = self::$options;
    $out_options[$selected]['selected'] = true;
    return $out_options;
  }

  public static function search_dates($option) {
    $offset = self::$options[$option]["offset"];
    $time = time();
    $day1 = day_info($time);

    if(is_int($offset)) {
      $day2 = day_info($time, $offset);
      if($offset > 0) {
        return array("start" => $day1['gdate'], "end" => $day2['date']);
      } else {
        return array("start" => $day2['gdate'], "end" => $day1['date']); 
      }
    } else {
      switch($offset) {
        case "term":
          if($day1['month_num'] < 7) {
            $end_date = "{$day1['year']}-07-01";
	  } else {
            $end_date = "{$day1['year']}-12-31";
          }
          break;

        case "year": 
          if($day1['month_num'] < 7) {
            $end_date = "{$day1['year']}-07-01";
	  } else {
            $year = $day1['year'] + 1;
            $end_date = "$year-07-01";
          }
          break;
      }    
      return array("start" => $day1['gdate'], "end" => $end_date); 
    }
  }
}

// URL DEFINITIONS
function dayURL($day, $type) {
  return "day.php?time={$day['time']}&type=$type";
}

function academicURL($year, $month) {
  return "academic.php";
}

function holidaysURL($year=NULL) {
  if(!$year) {
    $year = $_REQUEST['year'];
  }
  return "holidays.php?year=$year";
}

function religiousURL($year=NULL) {
  if(!$year) {
    $year = $_REQUEST['year'];
  }
  return "holidays.php?page=religious&year=$year";
}

function categorysURL() {
  return "categorys.php";
}

function categoryURL($category) {
  $id = is_array($category) ? $category['catid'] : $category->catid;
  return "category.php?id=$id";
}

function subCategorysURL($category) {
  $id = is_array($category) ? $category['catid'] : $category->catid;
  return "sub-categorys.php?id=$id";
}

function detailURL($event,$calid='all') {
  preg_match("/_(.*)$/i",$event->id->text,$matches);
  $id = $matches[1];
  return "detail.php?cal={$calid}&id={$id}";
}

function timeText($event) {

  $when = $event->getWhen();
  $startTime = $when[0]->startTime;
  $endTime = $when[0]->endTime;
  if (strlen($startTime) == 10) {
    $out = strftime('%a %b %e',strtotime($startTime));
  }
  else {
    $out = strftime('%a %b %e',strtotime($startTime))." ".strftime('%l:%M%P',strtotime($startTime));
    if ($endTime != '') {
      $out .= "-".strftime('%l:%M%P',strtotime($endTime));
    }
  }
  return $out;
}
  
function briefLocation($event) {
  $where = $event->getWhere();
  if ($where[0]) {
    return $where[0];
  } else {
    return false;
  }
}

function ucname($name) {
  $new_words = array();
  foreach(explode(' ', $name) as $word) {
    $new_word = array();
    foreach(explode('/', $word) as $sub_word) {
      $new_word[] = ucwords($sub_word);
    }
    $new_word = implode('/', $new_word);
    $new_words[] = $new_word;
  } 
  return implode(' ', $new_words);
}

class CalendarForm extends Form {

  protected $catid;
  protected $search_options;

  public function __construct($prefix, $search_options, $catid=NULL) {
    $this->prefix = $prefix;
    $this->catid = $catid;
    $this->search_options = $search_options;
  }

  public function out($total=NULL) {
    $catid = $this->catid;
    $search_options = $this->search_options;
    require "{$this->prefix}/form.html";
  }
}

?>
