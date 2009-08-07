<?

# - days it runs on
# - base sched w/ stops
# - between what and what delays (add/remove)

$routes = array();
$routes['blue_line'] = array(
                       "runs" => array(
                         array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri","Sat"),
                           "hour_per"     => 1, 
                           "hour_start"   => 6, 
                           "hour_end"     => 15, 
                           "delays"       => array("6:00"=>30,"7:30"=>30,"1:00"=>60),
                           "stops"        => array(
							  "Depot Leave"            => "00",
							  "Unity Manor"            => "02",
							  "Richwood & Charles"     => "05",
							  "DMV (Outbound)"         => "10",
							  "Airport & Mileground"   => "15",
							  "Easton Hill (Outbound)" => "20",
							  "University High School" => "30",
							  "Canyon Dairy Mart"      => "32",
							  "Lakeside Canyon"        => "35",
							  "Crest Point"            => "37",
							  "Easton Hill (Inbound)"  => "38",
							  "DMV (Inbound)"          => "47",
							  "Depot Return"           => "55"
						    ))));
											
function makeDD($minute,$hour_per,$hour_per_i) {
	$addition = array(0,30,20,15); #only supporting up to 4 runs per hour here
	if ($hour_per_i > 0) {
		$minute = $minute + ($addition[$hour_per_i] * $hour_per_i);
	}
	return $minute;
}

$db = db::$connection;
$stmt = $db->prepare("INSERT INTO TestSchedule (day_scheduled, day_real, route, place, hour, minute) values (?, ?, ?, ?, ?, ?)");	

	
										
foreach($routes as $route_name -> $route) {
  $runs = $line[0];
  $i = 0;
  foreach($runs as $run) {
    $days = $run[$i]['days'];
    
    foreach($days as $day_name -> $day) {
		$hour = $run[$i]['hour_start'];
		$hour_end = $run[$i]['hour_end'];
		$delay = 0;

	    while ($hour < $hour_end) {
			$stop_hour = $hour;
			$k = 0;
			
			while ($k < $run[$i]['hour_per']) {
				$prev_stop_hour = 0;
				$prev_stop_minute = 0;
				$delayed = false;
				$delay = 0;
				foreach($run[$i]['stops'] as $stop_name -> $stop) {
					$stop_minute = makeDD($stop[$stop_name],$run[$i]['hour_per'],$k);
					$stop_delay_check = $hour.":".$stop_minute;
					if (array_key_exists($stop_delay_check, $run[$i]['delays'])) {
						$delayed = true;
					    $delay = $run[$i]['delays'][$stop_delay_check];
					}
					if ($delayed && (($prev_stop_minute + $delay) > ((60/(int)$run[$i]['hour_per'])))) {
						$delayed = false;
						break; # get out of the foreach loop because we want to get to the next set of stops
					}
					else if ($delay < (60/(int)$run[$i]['hour_per'])) {
						$stop_minute = $stop_minute + $delay;
						if ($stop_minute > 59) {
							$stop_minute = $stop_minute - 60;
							if ($stop_minute < 10) {
								$stop_minute = "0".$stop_minute;
							}
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