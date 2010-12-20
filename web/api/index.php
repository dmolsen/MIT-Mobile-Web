<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* 
 * Use this to enable device detection in other services on your campus. They can send their user agent strings
 * to this script for classification. That way you only have to use one source for all your classification needs.
 * Ok, I'm tired and this is a lame write-up but hopefully it's fairly clear...
 *
 * To use:
 *
 *   - just send the user agent string you want to classify to:
 *
 *      http://m.institution.edu/api/?ua=user+agent+string
 *
 * NOTE: the user agent string must be url encoded.
 * 
 * This will return a JSON object with the following info:
 *
 *   - device name + any special version info (e.g. android22)
 *   - the name of the templates it'd be shown in mobile web osp (e.g. webkit)
 *   - true or false for if it's a computer, an android device, an ios device, an iphone, or an ipod
 *
 */

// require the detection class
require_once "../page_builder/detection.php";

// require the JSON service
require_once "../../lib/Services_JSON-1.0.2/JSON.php";

$user_agent = urldecode($_REQUEST['ua']);

$device = Device::classify($user_agent);
$templates = Device::templates($user_agent);

$device_info = array("device" => $device, 
					 "templates" => $templates, 
					 "is_computer" => Device::is_computer(),
					 "is_android" => Device::is_android(),
					 "is_ios" => Device::is_ios(),
					 "is_iphone" => Device::is_iphone(),
					 "is_ipod" => Device::is_ipod());

$json = new Services_JSON();

// if you're going to use JS to grab this data make sure to include a callback, jQuery does it
// auto-magically if you use json-p functions
if ($_REQUEST['callback']) {
	echo($_REQUEST['callback'].'('.$json->encodeUnsafe($device_info).')');
} else {
	echo($json->encodeUnsafe($device_info));
}

?>