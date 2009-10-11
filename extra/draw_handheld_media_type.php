<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

############################################################
### Check to see if mobi referral cookie has been set
### If not it writes out the alternate media link
### If you use this use *after* the mobile referrer check
############################################################

$redirect = "http://m.institutionname.edu";      # hostname to redirect mobile devices to
$regex    = "http\:\/\/m\.institutionname\.edu"; # hostname mobile requests are coming from, i know i over escape these things

if (!isset($_COOKIE["mobi_referral"])) {
  // just double-check the referrer just in case cookie hasn't been set
  if (!preg_match('/^'.$regex.'/i',$_SERVER['HTTP_REFERER'])) {
		echo("<link rel='alternate' media='handheld' xhref='".$redirect."' />");
  }
}

?>