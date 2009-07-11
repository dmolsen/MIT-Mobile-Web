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
     ->route("Cambridge East", "saferidecambeast", "SafeRide")
     ->summary("Runs every evening, all year round")
     ->perHour(2)
     ->stops(
	st("84 Mass. Ave"                   ,"mass84_d" , "frcamp", '00'),
        st("NW10 / Edgerton"                ,"nw10"     , "frcamp", '03'),
        st("NW30 / Warehouse"               ,"nw30"     , "frcamp", '04'),
        st("NW86 / 70 Pacific"              ,"nw86"     , "frcamp", '05'),
        st("NW61 / Random Hall"             ,"nw61"     , "frcamp", '06'),
        st("Main St @ Windsor St"           ,"mainwinds", "frcamp", '09'),
        st("Portland St @ Hampshire St"     ,"porthamp" , "frcamp", '10'),
        st("638 Cambridge St"               ,"camb638"  , "frcamp", '13'),
        st("Cambridge St @ Fifth St"        ,"camb5th"  , "frcamp", '14'),
        st("Sixth @ Charles St"             ,"6thcharl" , "tocamp", '15'),
        st("East Lot on Main St"            ,"elotmain" , "tocamp", '17'),
        st("Bld 66 (Ames St)"               ,"amesbld66", "tocamp", '18'),
        st("MIT Medical / 34 Carleton"      ,"mitmed"   , "tocamp", '20'),
        st("Kendall T"                      ,"kendsq"   , "tocamp", '22'),
        st("E40 / Wadsworth"                ,"wadse40"  , "tocamp", '23'),
        st("77 Mass. Ave"                   ,"mass77"   , "tocamp", '26'))    
     ->addHours("Thu-Sat",
       hours("18-22")->append(delay(5, "23 0 1 2 3:1"))
     ) 
     ->addHours("Sun-Wed",
       hours("18-21")->append(delay(5, "22 23 0 1 2:1")) 
     );


$schedule
     ->route("Cambridge West", "saferidecambwest", "SafeRide")
     ->summary("Runs every evening, all year round")
     ->perHour(2)
     ->stops(
	st("84 Mass. Ave"                   ,"mass84_d"  , "frcamp", '00'),
        st("W4 / McCormick"                 ,"mccrmk"    , "frcamp", '01'),
        st("W51 / Burton"                   ,"burtho"    , "frcamp", '02'),
        st("W70 / New House"                ,"newho"     , "frcamp", '03'),
        st("W85 / Tang / Westgate"          ,"tangwest"  , "frcamp", '04'),
        st("W79 / Simmons"                  ,"simmhl"    , "frcamp", '06'),
        st("WW15 (Request)"                 ,"ww15"      , "frcamp", '07'),
        st("Brookline St @ Chestnut St"     ,"brookchest", "frcamp", '09'),
        st("Putnum Ave @ Magazine St"       ,"putmag"    , "frcamp", '10'),
        st("River St @ Fairmont St"         ,"rivfair"   , "frcamp", '12'),
        st("River St @ Upton St"            ,"rivpleas"  , "frcamp", '13'),
        st("River St @ Franklin St"         ,"rivfrank"  , "tocamp", '14'),
        st("Sydney @ Green St"              ,"sydgreen"  , "tocamp", '16'),
        st("NW86 / 70 Pacific St"           ,"paci70"    , "tocamp", '18'),
        st("NW30 / Warehouse"               ,"whou"      , "tocamp", '19'),
        st("NW10 / Edgerton"                ,"edge"      , "tocamp", '20'))    
     ->addHours("Thu-Sat",
       hours("18-22")->append(delay(5, "23 0 1 2 3:1"))
     ) 
     ->addHours("Sun-Wed",
       hours("18-21")->append(delay(5, "22 23 0 1 2:1")) 
     );

$schedule
     ->route("Boston West", "saferidebostonw", "SafeRide")
     ->summary("Runs every evening, all year round")
     ->perHour(2)
     ->stops(
	st("84 Mass Ave"                    ,"mass84_d" , "boston", '15'),
        st("Mass Ave @ Beacon St"           ,"massbeac" , "boston", '18'),
        st("528 Beacon St"                  ,"beac528"  , "boston", '19'),
        st("487 Comm Ave"                   ,"comm487"  , "boston", '21'),
        st("64 Baystate"                    ,"bays64"   , "boston", '23'),
        st("111 Baystate"                   ,"bays111"  , "boston", '24'),
        st("155 Baystate"                   ,"bays155"  , "boston", '25'),
        st("259 St Paul St (ET)"            ,"stpaul259", "boston", '32'),
        st("58 Manchester (ZBT)"            ,"manc58"   , "mass84", '34'),
	st("550 Memorial Drive"             ,"memo550"  , "mass84", '40'),
        st("Simmons Hall"                   ,"simmhl"   , "mass84", '41'))
     ->addHours("Thu-Sat",
       hours("18-22")->append(delay(5, "23 0 1 2 3:1"))
     ) 
     ->addHours("Sun-Wed",
       hours("18-21")->append(delay(5, "22 23 0 1 2:1")) 
     );


$schedule
     ->route("Boston East", "saferidebostone", "SafeRide")
     ->summary("Runs every evening, all year round")
     ->perHour(2)
     ->stops(
	st("84 Mass. Ave"                  ,"mass84_d" ,"boston", '00'),
        st("Mass. Ave / Beacon St"         ,"massbeac" ,"boston", '02'),
        st("478 Comm. Ave"                 ,"comm478"  ,"boston", '04'),
        st("Vanderbilt (Request)"          , NULL      , NULL   , '06'),
        st("28 Fenway"                     ,"fenw28"   ,"boston", '10'),
        st("Prudential Center"             ,"prud"     ,"boston", '12'),
        st("229 Comm Ave"                  ,"comm229"  ,"boston", '15'),
        st("253 Comm Ave"                  ,"comm253"  ,"mass84", '16'),
        st("32 Hereford St"                ,"here32"   ,"mass84", '17'),
        st("450 Beacon St"                 ,"beac450"  ,"mass84", '18'),
        st("Beacon St @ Mass. Ave"         ,"beacmass" ,"mass84", '19'))
     ->addHours("Thu-Sat",
	 hours("18-22")->append(delay(5, "23 0 1 2 3:1"))
     ) 
     ->addHours("Sun-Wed",
	 hours("18-21")->append(delay(5, "22 23 0 1 2:1")) 
     );

$schedule
     ->route("Tech Shuttle", "tech")
     ->summary("Runs weekdays 7AM-6PM, all year round")
     ->except_holidays()
     ->perHour(3)
     ->stops(
	st("Kendall Square T"               ,"kendsq_d", "wcamp" , '15'),
        st("Amherst/Wadsworth"              ,"amhewads", "wcamp" , '17'),
        st("Media Lab"                      ,"medilb"  , "wcamp" , '18'),
        st("Building 39"                    ,"build39" , "wcamp" , '20'),
        st("84 Mass Avenue"                 ,"mass84"  , "wcamp" , '22'),
        st("Burton House"                   ,"burtho"  , "wcamp" , '24'),
        st("Audrey Street"                  ,"tangwest", "wcamp" , '26'),
        st("Simmons Hall"                   ,"simmhl"  , "kendsq", '27'),
        st("Vassar/Mass Ave"                ,"vassmass", "kendsq", '29'),
        st("Stata"                          ,"statct"  , "kendsq", '30'))
     ->addHours("Mon-Fri",
       hours("7-18"),
       delay(30, "7-9"),
       delay(-10, "16-17")
     ); 

$schedule
     ->route("Northwest Shuttle", "northwest")
     ->summary("Runs weekdays 7AM-6PM, all year round")
     ->except_holidays()
     ->perHour(3)
     ->stops(      
	st("Kendall Square T"          ,"kendsq_d" , "nwcamp", '25'),
	st("Amherst/Wadsworth"         ,"amhewads", "nwcamp", '27'),
        st("77 Mass Avenue"            ,"mass77"  , "nwcamp", '30'),
        st("MIT Museum (N52)"          ,"mitmus"  , "nwcamp", '32'),
        st("70 Pacific Street (NW86)"  ,"paci70"  , "kendsq", '34'),
        st("The Warehouse (NW30)"      ,"whou"    , "kendsq", '35'),
        st("Edgarton (NW10)"           ,"edge"    , "kendsq", '36'),
        st("Vassar/Mass Ave"           ,"vassmass", "kendsq", '39'),
        st("Stata"                     ,"statct"  , "kendsq", '41'))
     ->addHours("Mon-Fri",
       hours("7-17 18:1"),
       delay(10, "7-9")
     );  

$schedule
     ->route("Boston Daytime", "boston")
     ->summary("Runs weekdays 8AM-6PM, Sep-May")
     ->except_holidays()
     ->perHour(3)
     ->stops(      
	st("84 Mass. Ave."                  ,"mass84_d", "boston"   , '07'),
        st("Mass. Ave. / Beacon"            ,"massbeac", "cambridge", '09'),
        st("487 Comm. Ave. (PSK)"           ,"comm487" , "cambridge", '10'),
        st("64 Bay State (TXI)"             ,"bays64"  , "cambridge", '11'),
        st("478 Comm. Ave."                 ,"comm478" , "cambridge", '14'),
        st("450 Beacon St."                 ,"beac450" , "cambridge", '19'),
        st("77 Mass. Ave."                  ,"mass77"  , "cambridge", '23'))
     ->addHours("Mon-Fri", hours("8-17"));  
?>
