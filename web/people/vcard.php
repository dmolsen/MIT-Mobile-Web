<?php
/*
* Filename.......: vcard_example.php
* Author.........: Troy Wolf [troy@troywolf.com]
* Last Modified..: 2005/07/14 13:30:00
* Description....: An example of using Troy Wolf's class_vcard.
*/

require_once('lib/vcard.lib.php');
require_once('../../lib/ldap_services.php');

$error = false;
$send = false;

if (isset($_REQUEST["email"]) && isset($_REQUEST["username"])) {
    $person = lookup_username($_REQUEST["username"]);
    $person = html_escape_person($person);

    $vc = new vcard();

	$vc->data['first_name'] = $person["sn"][0];
	$vc->data['last_name'] = $person["givenname"][0];

    # Contact's company, department, title, profession
	$vc->data['company'] = "West Virginia University";
	$vc->data['department'] = $person["dept"][0];
	$vc->data['title'] = $person["title"][0];

    # Contact's work address
	$vc->data['work_address'] = $person["address"][0];
	if (preg_match('/PO Box/i',$person["address"][1])) {
		$vc->data['work_po_box'] = $person["address"][1];
		$city_state_zip = explode(", ", $person["address"][2]);
		$vc->data['work_city'] = $city_state_zip[0];
		$vc->data['work_state'] = $city_state_zip[1];
		$vc->data['work_postal_code'] = $city_state_zip[2];
	}
	else {
		$city_state_zip = explode(", ", $person["address"][1]);
		$vc->data['work_city'] = $city_state_zip[0];
		$vc->data['work_state'] = $city_state_zip[1];
		$vc->data['work_postal_code'] = $city_state_zip[2];
	}
	
	$vc->data['work_country'] = "United States of America";

    # Contact's telephone numbers.
	$vc->data['office_tel'] = $person["telephone"][0];
	$vc->data['home_tel'] = $person["homephone"][0];
	$vc->data['fax_tel'] = $person["fax"][0];

    #Contact's email addresses
	$vc->data['email1'] = $person["email"][0];

	$vc->download();
	
	$send = true;
	require "$prefix/vcard.html";
}
else if (isset($_REQUEST["username"])) {
	require "$prefix/vcard.html";
}
else {
   $error = true;
   $message = "You need to supply a valid username to use the Vcard system.";
   require "$prefix/vcard.html";
}




?>