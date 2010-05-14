<?

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../../config.gen.inc.php";

// we don't record stats for this feature

// libs
require_once "../../../lib/ldap_services.php";
require_once "PEAR.php";
require_once "../../../lib/Services_JSON-1.0.2/JSON.php";

# the secret word must be shared between iPhone app and this page
# the secret word just limits who can access the service. should use a timestamp too really.
$secret_word = ''; 

$search_term = $_REQUEST['q'];
$key = strtolower($_REQUEST['key']);

if ($search_term == '') {
	echo("Error: No Query");
}
else if ($key == '') {
	echo("Error: No Key");
}
else if (strlen($search_term) > 50) {
	echo("Error: Too much data");
}
else if (strlen($key) > 32) {
	echo("Error: Too much data");
}
else if ($key == md5($search_term.$secret_word)) {
	$raw_people = mit_search($_REQUEST['q']);
	$people = Array();
	foreach ($raw_people as $raw_person) {
		$person = Array();
		foreach ($raw_person as $attribute => $value) {
		  if ($value) $person[$attribute] = $value;
		}
		$people[] = $person;
	}
	$total = count($people);
	$result = Array(
		'resultSet' => Array(
		'totalResultsAvailable' => $total,
		'totalResultsReturned' => $total,
		'firstResultPosition' => 1,
		'result' => $people),
	);
	$json = new Services_JSON();
	echo($json->encodeUnsafe($result));
}
else {
	echo("Error: Bad Key");
}


?>