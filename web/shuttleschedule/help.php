<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


$header = "Shuttle Schedule";
$module = "shuttleschedule";

$help = array(
  'Note: GPS tracking functionality is currently not available.',

  'Find the expected time when an MIT shuttle bus should arrive at each stop, based on the official schedule published by the MIT Parking Office. For each currently running route, the next scheduled stop is indicated by a red highlight and a red shuttle-bus icon.', 

  'The estimated times shown on the route detail page are estimates based on the current time (the moment the page is loaded on your device); and the published schedule; actual times may be affected by weather, road and traffic conditions. To update the estimated times and the route map, click the &lsquo;Refresh&rsquo; link near the top of the page or use your web browser&apos;s &lsquo;Refresh&rsquo; command.',

  'Each route detail page includes a full route map highlighting the estimated next stop. To see this map, scroll down (for feature phones and smartphones) or use the Route Map tab (iPhone and iPod Touch). On the iPhone and iPod Touch, rotating your device will display the schedule and route map side-by-side.',
);

require "../page_builder/help.php";

?>
