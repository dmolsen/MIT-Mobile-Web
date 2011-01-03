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
$adapter = ModuleAdapter::find('map');
require_once "../map/adapters/".$adapter."/adapter.php";

// libs
require_once "../map/lib/map.lib.inc.php";

$results = MapAdapter::searchPlaces($filter);
$total = count($results);

require "../map/templates/$prefix/federated.html";

?>
