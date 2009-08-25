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
#  --- hour start
#  --- loops (how many times does it run it's route)
#  --- length (how long, in minutes, is each loop)
#  --- delays** which have:
#  ------ full stop time that the delay starts
#  ------ the time in minutes for the delay
#  --- stops to skip (need to match the times generated for DB)
#  --- stops which have:
#  ------ name of stop
#  ------ minute the stop regularly occurs
#
#  ** - delays are tricky. if the delay is less than a full loop the stops will start with their base time + delay.
#       this can cause issues because the hour doesn't get properly incremented for a loop so make sure to take that into account
#       when restarting from a delay.
#
# to skip loops, especially in the morning or evening, requires that you skip each loop individually using a delay equal to the
# time it takes to do a full loop.

$routes = array();
$routes['blue_line'] = array(
                       "runs" => array(
                         array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri"), 
                           "hour_start"   => 6, 
                           "loops"        => 10,
                           "length"       => 60, 
						   "busnum"       => 1,
                           "delays"       => array("6:00"=>30,"7:00"=>60,"13:00"=>60,"17:00"=>10),
                           "stops_skip"   => array("5:48","5:57","6:05"),
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
                           "hour_start"   => 6, 
                           "loops"        => 44,
                           "length"       => 20, 
						   "busnum"       => 1,
                           "delays"       => array("6:00"=>20, "6:20"=>20),
                           "stops_skip"   => array(),
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
	                           "hour_start"   => 9,
	                           "loops"        => 27,
	                           "length"       => 20,
							   "busnum"       => 1,
	                           "delays"       => array("9:00"=>20,"9:20"=>20),
	                           "stops_skip"   => array(),
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

# skipped campus pm (need three busses at once)
# skipped cassville

$routes['crown'] = array(
                       "runs" => array(
                         array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri","Sat"),
                           "hour_start"   => 7, 
                           "loops"        => 1,
                           "length"       => 70,
						   "busnum"       => 1, 
                           "delays"       => array(),
                           "stops_skip"   => array(),
                           "stops"        => array(
							  "Depot Leave"                  => "00",
							  "Westover Triangle"            => "05",
							  "Westover Terminal (Outbound)" => "10",
							  "Laurel Point"                 => "15",
							  "Amettsville School"           => "23",
							  "Crown Turnaround"             => "26",
							  "Everettsville"                => "33",
							  "Opekisika Damn"               => "35",
							  "Lawless Road"                 => "38",
							  "Booth"                        => "44",
							  "Waitman Barb Elementary"      => "45",
							  "Westover Terminal (Inbound)"  => "52",
							  "Depot Return"                 => "10"
						    )),
						array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri","Sat"), 
                           "hour_start"   => 13, 
                           "loops"        => 1,
                           "length"       => 70,
						   "busnum"       => 1,
                           "delays"       => array(),
                           "stops_skip"   => array(),
                           "stops"        => array(
							  "Depot Leave"                  => "00",
							  "Westover Triangle"            => "05",
							  "Westover Terminal (Outbound)" => "10",
							  "Laurel Point"                 => "15",
							  "Amettsville School"           => "23",
							  "Crown Turnaround"             => "26",
							  "Everettsville"                => "33",
							  "Opekisika Damn"               => "35",
							  "Lawless Road"                 => "38",
							  "Booth"                        => "44",
							  "Waitman Barb Elementary"      => "45",
							  "Westover Terminal (Inbound)"  => "52",
							  "Depot Return"                 => "05"
						    )),
						array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri","Sat"), 
                           "hour_start"   => 17, 
                           "loops"        => 1,
                           "length"       => 70,
						   "busnum"       => 1,
                           "delays"       => array(),
                           "stops_skip"   => array(),
                           "stops"        => array(
							  "Depot Leave"                  => "15",
							  "Westover Terminal (Outbound)" => "21",
							  "Waitman Barb Elementary"      => "34",
							  "Booth"                        => "35",
							  "Lawless Road"                 => "41",
							  "Opekisika Dam"                => "45",
							  "Everettsville"                => "46",
							  "Crown Turnaround"             => "51",
							  "Amettsville School"           => "57",
							  "Laurel Point"                 => "05",
							  "Westover Terminal Return"     => "12"
						    ))));

$routes['downtown_pm_mall'] = array(
                       "runs" => array(
                         array(
                           "days"         => array("Mon","Tue","Wed","Thu","Fri","Sat"), 
                           "hour_start"   => 18, 
                           "loops"        => 13,
                           "length"       => 60,
                           "busnum"       => 1,
                           "delays"       => array(),
                           "stops_skip"   => array(),
                           "stops"        => array(
							  "Towers"                   => "00",
							  "Willowdale (Stadium end)" => "04",
							  "Sunnyside"                => "08",
							  "Mountainlair"             => "09",
							  "Boreman Hall"             => "11",
							  "Warner Theater"           => "12",
							  "Westover Triangle"        => "16",
							  "Westover Park & Ride"     => "18",
							  "Westover Terminal"        => "20",
							  "Morgantown Mall Theater"  => "24",
							  "Kmart"                    => "27",
							  "Public Safety Building"   => "40",
							  "Boreman Hall"             => "41",
							  "Mountainlair"             => "43"
						    ))));

# skipped brown line

?>
