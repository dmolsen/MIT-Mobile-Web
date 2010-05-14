<?

// URL DEFINITIONS
function dayURL($day, $type) {
  return "/calendar/day.php?time={$day['time']}&type=$type";
}

function academicURL($year, $month) {
  return "/calendar/academic.php";
}

function categorysURL() {
  return "/calendar/categorys.php";
}

function categoryURL($category) {
  $id = is_array($category) ? $category['catid'] : $category->catid;
  return "/calendar/category.php?id=$id";
}

function detailURL($event,$calid='all') {
  preg_match("/_(.*)$/i",$event->id->text,$matches);
  $id = $matches[1];
  return "/calendar/detail.php?cal={$calid}&id={$id}";
}

function phoneURL($number) {
  if($number) {

    // add the local area code if missing
    if(preg_match('/^\d{3}-\d{4}/', $number)) {
      $number = '617' . $number;
    }

    // check if the number is short number such as x4-2323, 4-2323, 42323
    if(preg_match('/^\d{5}/', $number)) {
      $first_digit = substr($number, 0, 1);
    } elseif(preg_match('/^x\d/', $number)) {
      $number = substr($number, 1);
      $first_digit = substr($number, 0, 1);
    } elseif(preg_match('/^\d-\d{4}/', $number)) {
      $first_digit = substr($number, 0, 1);
    }

    // if short number add the appropriate prefix and area code
    $prefixes = array('252', '253', '324', '225', '577', '258');
    if($first_digit) {
      foreach($prefixes as $prefix) {
        if(substr($prefix, -1) == $first_digit) {
          $number = "617" . substr($prefix, 0, 2) . $number;
          break;
        }  
      }
    }

    // remove all non-word characters from the number
    $number = preg_replace('/\W/', '', $number);
    return "tel:1$number";
  }
}

function mapURL($event) {
	$db = new db;
	$sql = "SELECT * FROM Buildings WHERE type != 'Parking Lot' OR type != 'Public Parking' GROUP BY name ORDER BY name ASC";
	$stmt = $db->connection->prepare($sql);
	$stmt->execute();
	$results = $stmt->fetchAll();
	
	# compare the name of the building in the event against each building in the db, brute force and doesn't always work
	foreach ($results as $result) {
		if (preg_match('/'.$result['name'].'/i',$event)) {
			return "<a href='/map/detail.php?loc=".$result['id']."&lat=".$result['latitude']."&long=".$result['longitude']."&maptype=roadmap'>".$event."</a>";
		}
	}
	
	return $event;
}

function URLize($web_address) {
  if(preg_match('/^http\:\/\//', $web_address)) {
    return $web_address;
  } else {
    return 'http://' . $web_address;
  }
}

function timeText($event) {

  $when = $event->getWhen();
  $startTime = $when[0]->startTime;
  $endTime = $when[0]->endTime;
  if (strlen($startTime) == 10) {
    $out = strftime('%A, %b. %e, %G',strtotime($startTime));
  }
  else {
    $out = strftime('%A, %b. %e, %G',strtotime($startTime))." ".strftime('%l:%M%P',strtotime($startTime));
    if ($endTime != '') {
      $out .= "-".trim(strftime('%l:%M%P',strtotime($endTime)));
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

function getExtraData($description,$extra_data_pattern,$urlize) {
	$extra_data = '';
        $pattern = '/\[\['.$extra_data_pattern.'\]\](.*)\[\[\/'.$extra_data_pattern.'\]\]/';
        if (preg_match($pattern,$description,$matches)) {
	  if (trim($matches[1] != '')) {
			if ($urlize) {
				$extra_data = URLize($matches[1]);
			}
			else {
				$extra_data = $matches[1];
			}
		}
		$description = preg_replace($pattern,'',$description);
	}
	return array($description,$extra_data);
}

?>