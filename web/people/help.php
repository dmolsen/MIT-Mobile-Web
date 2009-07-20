<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


$header = "People Directory";
$module = "people";

# check to see if there is an on-campus prefix (set in config.inc.php)
if ($has_oc_prefix) {
	$prefix = ', &quot;'.$oncampus_prefix.'1000&quot;';
}
else {
	$prefix = '';
}

# check to see if there is an voice assisted directory search (set in config.inc.php)
if ($has_dir_search_va) {
	$va_dir_search = 'If you run into difficulty, please try calling '.$dir_search_va_num.' for voice-assisted directory search.';
}
else {
	$va_dir_search = '';
}

$help = array(
  'Search for '.$inst_name.' students, faculty, staff, and affiliates by part or all of their name, email address, or phone number.',

  'Example: To find Alexander Martin, you could search by:<br />' .
  '- <strong>Name</strong> (full or partial): e.g., &quot;Alexander Martin,&quot; &quot;alex mar&quot;, &quot;a martin&quot;, &quot;alex&quot;, etc.<br />' .
  '- <strong>Email address</strong>: &quot;almartins&quot;, &quotalmartins@'.$email_ending.'&quot;<br />' .
  '- <strong>Phone number</strong> (full or partial):  e.g., &quot;'.$area_code.$exchange.'1000&quot;'.$prefix,

  'Depending on the person that you looked up and the capabilities of your mobile device, you can call or email the person directly, or find their office on the campus map.',

  $va_dir_search,
);

require "../page_builder/help.php";

?>
