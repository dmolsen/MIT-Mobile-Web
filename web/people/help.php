<?
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

require_once "../../config.gen.inc.php";

$header = $inst_name." People Directory";
$module = "people";

# check to see if there is an on-campus prefix (set in config.gen.inc.php)
if ($has_oc_prefix) {
	$prefix = ', &quot;'.$oncampus_prefix.'1000&quot;';
}
else {
	$prefix = '';
}

# check to see if there is an voice assisted directory search (set in config.gen.inc.php)
if ($has_dir_search_va) {
	$va_dir_search = 'If you run into difficulty, please try calling '.$dir_search_va_num.' for voice-assisted directory search.';
}
else {
	$va_dir_search = '';
}

$help = array(
  'Search for '.$inst_name.' students, faculty, staff, and affiliates by part or all of their name, email address, or phone number.',

  'Example: To find Alexander Martin, you could search by:' .
  '<ol><li><strong>Name</strong> (full or partial): e.g., &quot;Alexander Martin,&quot; &quot;alex mar&quot;, &quot;a martin&quot;, &quot;alex&quot;, etc.</li>' .
  '<li><strong>Email address</strong>: &quot;amartins&quot;, &quot;amartins@'.$email_ending.'&quot;</li>' .
  '<li><strong>Phone number</strong> (full or partial):  e.g., &quot;'.$area_code.$exchange.'1000&quot;'.$prefix.'</li></ol>',

  'Depending on the person that you looked up and the capabilities of your mobile device, you can call or email the person directly, or find their office on the campus map.',

  $va_dir_search,
);

require "../page_builder/help.php";

?>
