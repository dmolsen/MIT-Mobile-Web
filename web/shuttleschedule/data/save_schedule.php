<?

require_once "../../../lib/db.php";
require_once "shuttle_schedule_import.inc.php";
											
function makeDD($minute,$loop,$length,$delay) {
	$minute = $minute + $delay + ($loop * $length);
	if ($minute == 60) {
		$minute = 0;
	}
	else if ($minute > 60) {
		$minute = $minute % 60;
	}
        if ($minute < 10) {
          $minute = "0".$minute;
        }
	return $minute;
}


$db = new db;
$stmt = $db->connection->prepare("INSERT INTO Schedule (day_scheduled, day_real, route, place, hour, minute, busnum) values (?, ?, ?, ?, ?, ?, ?)");	
								
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

        $prev_stop_hour = 0;
        $prev_stop_minute = 0;
        $delayed = false;
        $delay = 0;
        $stop_hour = $hour;

	    while ($loop < ($run['loops'])) {
		   /*if ($loop == 0) {
			  $stop_hour = $hour;
		        }
	                else {
			  $stop_hour = $hour + (floor(($run['length']*$loop)/60));
			}
			
			$k = 0;
			$prev_stop_hour = 0;
			$delayed = false;*/
			$delay = 0;
			$breakfor = false;
			$stop_hour_plus = false;
			$prev_stop_minute = 0;
			$stop = 0;
			
			foreach($run['stops'] as $stop_name => $stop_minute) {
				$stop_minute = $stop_minute + $delay + ($loop * $run['length']);
                $stop_hour = $hour + floor(($run['length']*$loop)/60);
				if ($stop_minute == 60) {
					$stop_minute = 0;
			        if ($stop > 0) {
					  $stop_hour_plus = true;
				    }
				}
				else if ($stop_minute > 60) {
					$stop_minute = $stop_minute % 60;
				}
			    if ($stop_minute < 10) {
			        $stop_minute = "0".$stop_minute;
			    }
			
			    if ($stop_minute < $prev_stop_minute) {
				    $stop_hour_plus = true;
			    }
				#echo($stop_minute); exit;
				$stop_delay_check = $stop_hour.":".$stop_minute;
				if (array_key_exists($stop_delay_check, $run['delays'])) {
				    $delay = $run['delays'][$stop_delay_check]; 
				    if ($delay < ($run['length'])) {
					   $stop_minute = $stop_minute + $delay;
					   if ($stop_minute > 59) {
						   $stop_minute = $stop_minute - 60;
						   $stop_hour = $stop_hour + 1;
					   }
				    }
				    else {
                       #echo("got here ".$delay." ".$stop_delay_check." ".$stop_hour." ".$stop_minute." ".$loop."\n");
					   $breakfor = true;
					   #break; # get out of the foreach loop because we want to get to the next set of stops
				    }
                }
                if ($breakfor == true) {
	 				$breakfor = false;
	                break;
                }
			    if ($stop_hour_plus == true) {
				   #$stop_hour_plus = false;
				   $stop_hour = $stop_hour + 1;
			    }
				if ($stop_hour > 23) {
                   $stop_hour = $stop_hour - 24;
                }

				if (db::$use_sqlite) {
					$stmt->execute(array($day_name, $day_name, $route_name, $stop_name, $stop_hour, $stop_minute, $busnum));
				}
				else {
					$stmt->bind_param('ssssiii', $day_name, $day_name, $route_name, $stop_name, $stop_hour, $stop_minute, $busnum);
				    $stmt->execute();
				}
				$prev_stop_minute = $stop_minute; 
				$stop++;
			}			
			$loop++;
	    }
    }

    $i++;
  }
}
	
?>
