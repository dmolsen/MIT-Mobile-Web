<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/**
 * This script is simply used to provide customized search results for the federated search module
 */

// sets up adapter class
require_once "../page_builder/adapter.php";
$adapter = ModuleAdapter::find('calendar');
require_once "../calendar/adapters/".$adapter."/adapter.php";

// libs
require_once "lib/calendar.lib.php";
require_once "lib/textformat.lib.php";

$dates = SearchOptions::search_dates(3); // next 30 days

$results = CalendarAdapter::searchEvents($filter,$dates['start'],$dates['end']);
$total = count($results);

require "../calendar/templates/$prefix/federated.html";

?>