<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "/apache/htdocs/Mobi-Demo/web/shuttleschedule/lib/ShuttleSchedule.php";

$schedule = new ShuttleSchedule();

$schedule
     ->route("Blue Line", "blue_line")
     ->summary("Runs Monday through Saturday, all year round")
     ->perHour(1)
     ->stops(
	    st("Depot Leave"             ,"depotl"     , "frdpot", '00'),
        st("Unity Manor"             ,"unitymanor" , "frdpot", '02'),
        st("Richwood & Charles"      ,"randc"      , "frdpot", '05'),
        st("DMV (Outbound)"          ,"dmvo"       , "frdpot", '10'),
        st("Airport & Mileground"    ,"aandm"      , "frdpot", '15'),
        st("Easton Hill (Outbound)"  ,"eastonhillo", "frdpot", '20'),
        st("University High School"  ,"uhs"        , "frdpot", '30'),
        st("Canyon Dairy Mart"       ,"cdmart"     , "todpot", '32'),
        st("Lakeside Canyon"         ,"lakecanyon" , "todpot", '35'),
        st("Crest Point"             ,"crestpoint" , "todpot", '37'),
        st("Easton Hill (Inbound)"   ,"eastonhilli", "todpot", '38'),
        st("DMV (Inbound)"           ,"dmvi"       , "todpot", '47'),
        st("Depot Return"            ,"depotr"     , "todpot", '55'))    
     ->addHours("Mon-Sat",hours("6-17"));


$schedule
     ->route("Blue and Gold Connector", "blue_and_gold_connector")
     ->summary("Runs every day, all year round")
     ->perHour(3)
     ->stops(
	st("Brooke Towers (Rawley St.)" ,"btowersl" , "frbrke", '00'),
        st("Law School"          ,"law"      , "frbrke", '01'),
        st("Grant Avenue"        ,"grant"    , "frbrke", '02'),
        st("Summit Hall"         ,"summit"   , "frbrke", '04'),
        st("Life Sciences"       ,"lifes"    , "frbrke", '05'),
        st("Beechurst & 6th"     ,"beech6th" , "tobrke", '08'),
        st("CAC & Engineering"   ,"caccemr"  , "tobrke", '10'))
        #st("Brooke Towers Return","btowersr" , "tobrke", '19'))    
     ->addHours("Mon-Sat",hours("6-23"));

$schedule
     ->route("Downtown PM Mall", "downtown_pm_mall")
     ->summary("I don't care")
     ->perHour(1)
     ->addHours("Mon-Sat",hours("6-23"));
?>
