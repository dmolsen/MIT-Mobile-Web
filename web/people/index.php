<?

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// libs
require_once "../../lib/ldap_services.php";
require_once "lib/textformat.lib.php";

if ($search_terms = $_REQUEST["filter"]) {
} else {
	$search_terms = "";
}

if (isset($_REQUEST["username"])) {
   $person = lookup_username($_REQUEST["username"]);
   $person = html_escape_person($person);
   require "templates/$prefix/detail.html";
} else if ($search_terms) {

   //search mit ldap directory
   $people = mit_search($search_terms);
   $people = html_escape_people($people);

   $total = count($people);

   if ($total==0) {
		$failed_search = True;
		require "templates/$prefix/index.html";
   } elseif($total==1) {
		$person = $people[0];
		require "templates/$prefix/detail.html";
   } else {
		if ($prefix != 'webkit') {
			$content = new ResultsContent("items", "people", $prefix, $phone); 
		} 
		require "templates/$prefix/results.html";
   }
} else {
   require "templates/$prefix/index.html";
}

$page->output();

?>
