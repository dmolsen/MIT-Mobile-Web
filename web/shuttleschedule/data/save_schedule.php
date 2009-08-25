<?

require_once "../../../lib/db.php";
require_once "shuttle_schedule_import.inc.php";
											
function makeDD($minute,$loop,$length) {
	$minute = $minute + ($loop * $length);
	if ($minute == 60) {
		$minute = 0;
	}
	else if ($minute > 60) {
		$minute = $minute % 60;
	}
	return $minute;
}

$db = db::$connection;
$stmt = $db->prepare("INSERT INTO Schedule (day_scheduled, day_real, route, place, hour, minute, busnum) values (?, ?, ?, ?, ?, ?, ?)");	
								
foreach($routes as $route_name => $route) {
  $runs = $route['runs'];
  $i = 0;
  foreach($runs as $run) {
    $days = $run['days'];
    
    foreach($days as $day_name) {
		$hour = $run['hour_start'];
		#$hour_end = $run['hour_end'];
		$busnum = $run['busnum'];
		#$break = $run['break'];
		$delay = 0;
		$loop = 0;

	    while ($loop < ($run['loops'])) {
		    if ($loop == 0) {
			  $stop_hour = $hour;
		    }
	        else {
			  $stop_hour = $hour + (floor(($run['length']*$loop)/60));
			}
			$stop_hour = $mins = floor ($seconds / 60);
			$k = 0;
			$prev_stop_hour = 0;
			$prev_stop_minute = 0;
			$delayed = false;
			$delay = 0;
			
			foreach($run['stops'] as $stop_name => $stop) {
				$stop_minute = makeDD($stop,$loop,$run['length']);
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
				if ($delay < ($run['length'])) {
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
					$stmt->execute(array($day_name, $day_name, $route_name, $stop_name, $stop_hour, $stop_minute, $busnum));
				}
				else {
					$stmt->bind_param('ssssiii', $day_name, $day_name, $route_name, $stop_name, $stop_hour, $stop_minute, $busnum);
				    $stmt->execute();
				}
				
				$prev_stop_hour = $stop_hour;
				$prev_stop_minute = $stop_minute;
			}
			
			$loop++;
	    }
    }

    $i++;
  }
}
	
?>
