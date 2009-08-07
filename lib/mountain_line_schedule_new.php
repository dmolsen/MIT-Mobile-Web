<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

# needed to create my own method to dump data into the bus schedule db since i couldn't
# figure out MIT set-up. here's the layout:
#  - you can have multiple routes (bus lines). 
#  - routes can have multiple runs (just a way to split different times for same route across different days)
#  - runs have:
#  --- days they run on (Sun, Mon, Tue, Wed, Thu, Fri, Sat)
#  --- number of times it loops an per hour (system currently only supports 4 loops, eg every 15 minutes)
#  --- hour start
#  --- hour end (use 24 hr clock)
#  --- delays** which have:
#  ------ full stop time that the delay starts
#  ------ the time in minutes for the delay
#  --- stops which have:
#  ------ name of stop
#  ------ minute the stop regularly occurs
#
#  ** - delays are tricky. if the delay is less than a full loop the stops will start with their base time + delay.
#       this can cause issues because the hour doesn't get properly incremented for a loop so make sure to take that into account
#       when restarting from a delay.

$routes = array();
$routes['blue_line'] = array(
                       "runs" => array(
                         array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri","Sat"),
                           "hour_per"     => 1, 
                           "hour_start"   => 6, 
                           "hour_end"     => 18, 
                           "delays"       => array("6:00"=>30,"7:00"=>60,"13:00"=>60),
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

$routes['blue_and_gold_connector'] = array(
                       "runs" => array(
                         array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri"),
                           "hour_per"     => 3, 
                           "hour_start"   => 6, 
                           "hour_end"     => 21, 
                           "delays"       => array("6:00"=>20, "6:20"=>20),
                           "stops"        => array(
							  	"Brooke Towers (Rawley St.)"  => "00",
								 "Law School"                 => "01",
								 "Grant Avenue"               => "02",
								 "Summit Hall"                => "04",
								 "Life Sciences"              => "05",
								 "Beechurst & 6th"            => "08",
								 "CAC & Engineering"          => "10"
						    )),
						    array(
	                           "days"         => array("Sat","Sun"),
	                           "hour_per"     => 3, 
	                           "hour_start"   => 9, 
	                           "hour_end"     => 19, 
	                           "delays"       => array("9:00"=>20,"9:20"=>20,"6:40"=>20),
	                           "stops"        => array(
								  "Brooke Towers (Rawley St.)" => "00",
								  "Law School"                 => "01",
								  "Grant Avenue"               => "02",
								  "Summit Hall"                => "04",
								  "Life Sciences"              => "05",
								  "Beechurst & 6th"            => "08",
								  "CAC & Engineering"          => "10"
							))
						));

?>
