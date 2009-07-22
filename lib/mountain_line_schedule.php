<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "ShuttleSchedule.php";

$schedule = new ShuttleSchedule();

$schedule
     ->route("Blue Line", "blueline")
     ->summary("Runs Monday through Saturday, all year round")
     ->perHour(1)
     ->stops(
	st("Depot"                   ,"depot" , "frcamp", '00'),
        st("Unity Manor"                ,"unitymanor"     , "frcamp", '02'),
        st("Richwood & Charles"               ,"randc"     , "frcamp", '05'),
        st("DMV"              ,"dmv"     , "frcamp", '10'),
        st("Airport & Mileground"             ,"aandm"     , "frcamp", '15'),
        st("Easton Hill"           ,"eastonhill", "frcamp", '20'),
        st("University High School"     ,"uhs" , "frcamp", '30'),
        st("Canyon Dairy Mart"               ,"cdmart"  , "frcamp", '32'),
        st("Lakeside Canyon"        ,"lakecanyon"  , "frcamp", '35'),
        st("Crest Point"             ,"crestpoint" , "tocamp", '37'),
        st("Easton Hill"            ,"eastonhill" , "tocamp", '38'),
        st("DMV"               ,"dmv", "tocamp", '47'),
        st("Depot"      ,"depot"   , "tocamp", '55'))    
     ->addHours("Mon-Sat",
       hours("6-17")
     ) ;


$schedule
     ->route("Blue & Gold Connector", "bluegoldconnector")
     ->summary("Runs every day, all year round")
     ->perHour(3)
     ->stops(
	st("Brooke Towers"                   ,"btowers"  , "frcamp", '00'),
        st("Law School"                 ,"law"    , "frcamp", '01'),
        st("Grant Avenue"                   ,"grant"    , "frcamp", '02'),
        st("Summit Hall"                ,"summit"     , "frcamp", '04'),
        st("Life Sciences"          ,"tangwest"  , "frcamp", '05'),
        st("Beechurst & 6th"                  ,"beech6th"    , "frcamp", '08'),
        st("CAC & Engineering"                 ,"caccemr"      , "frcamp", '10'),
        st("Brooke Towers"     ,"btowers", "frcamp", '20'))    
     ->addHours("Mon-Sun",
       hours("6-23")
     );

?>
