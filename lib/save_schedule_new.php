<?

require_once "db.php";
require_once "mountain_line_schedule_new.php";
											
function makeDD($minute,$hour_per,$hour_per_i) {
	$addition = array(0,30,20,15); #only supporting up to 4 loops per hour here
        if ($hour_per_i > 0) {
		$minute = $minute + ($addition[$hour_per-1] * $hour_per_i);
	}
	return $minute;
}

$db = db::$connection;
$stmt = $db->prepare("INSERT INTO TestSchedule (day_scheduled, day_real, route, place, hour, minute) values (?, ?, ?, ?, ?, ?)");	
								
foreach($routes as $route_name => $route) {
  $runs = $route['runs'];
  $i = 0;
  foreach($runs as $run) {
    $days = $run['days'];
    
    foreach($days as $day_name) {
		$hour = $run['hour_start'];
		$hour_end = $run['hour_end'];
		$delay = 0;

	    while ($hour < $hour_end) {
			$stop_hour = $hour;
			$k = 0;
			
			while ($k < $run['hour_per']) {
				$prev_stop_hour = 0;
				$prev_stop_minute = 0;
				$delayed = false;
				$delay = 0;
				foreach($run['stops'] as $stop_name => $stop) {
					$stop_minute = makeDD($stop,$run['hour_per'],$k);
					#echo($stop_minute); exit;
                                        $stop_delay_check = $hour.":".$stop_minute;
					if (array_key_exists($stop_delay_check, $run['delays'])) {
						$delayed = true;
					    $delay = $run['delays'][$stop_delay_check];
					}
					#if ($delayed && (($prev_stop_minute + $delay) > ((60/(int)$run['hour_per'])))) {
					#	$delayed = false;
					#	break; # get out of the foreach loop because we want to get to the next set of stops
					#}
					#else 
					if ($delay < (60/(int)$run['hour_per'])) {
						$stop_minute = $stop_minute + $delay;
						if ($stop_minute > 59) {
							$stop_minute = $stop_minute - 60;
							$delayed = true;
						}
					}
					else {
						$delayed = false;
						break; # get out of the foreach loop because we want to get to the next set of stops
					}
					if ((int)$stop_minute < (int)$prev_stop_minute) {
					   if ($stop_hour == $prev_stop_hour) {
					      $stop_hour = $stop_hour + 1;
					   }
					}
					else if ($stop_hour < $prev_stop_hour) {
					   $stop_hour = $prev_stop_hour;
					}
					
					if ($stop_minute < 10) {
						$stop_minute = "0".$stop_minute;
					}

					if (db::$use_sqlite) {
						$stmt->execute(array($day_name, $day_name, $route_name, $stop_name, $stop_hour, $stop_minute));
					}
					else {
						$stmt->bind_param('ssssii', $day_name, $day_name, $route_name, $stop_name, $stop_hour, $stop_minute);
					    $stmt->execute();
					}
					
					$prev_stop_hour = $stop_hour;
					$prev_stop_minute = $stop_minute;
				}
				$k++;
			}
			$hour++;
	    }
    }

    $i++;
  }
}
	
?>
