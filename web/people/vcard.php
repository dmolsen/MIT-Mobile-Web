<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/*
* Based on:
* Filename.......: vcard_example.php
* Author.........: Troy Wolf [troy@troywolf.com]
* Last Modified..: 2005/07/14 13:30:00
* Description....: An example of using Troy Wolf's class_vcard.
*/

// various copy includes
require_once "../../config.gen.inc.php";

// records stats
require_once "../page_builder/page_header.php";

// libs
require_once('lib/vcard.lib.php');
require_once('../../lib/ldap_services.php');

$error = false;
$send = false;

if (isset($_REQUEST["email"]) && isset($_REQUEST["username"])) {
	
	if (preg_match('/([A-z]{3})([0-9]{3})/i',$_REQUEST["username"]) && (preg_match('/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/',$_REQUEST['email']))) {
		
   	    $person = lookup_username($_REQUEST["username"]);
	    $person = html_escape_person($person);

	    $vc = new vcard();

			$vc->data['first_name'] = $person["givenname"][0];
			$vc->data['last_name'] = $person["surname"][0];

		    # Contact's company, department, title, profession
			$vc->data['company'] = $inst_name_full; # from config.gen.inc.php
			$vc->data['department'] = $person["dept"][0];
			$vc->data['title'] = $person["title"][0];

		    # Contact's work address
		    if ($person['affiliation'][0] == 'facstaff') {
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
		    }

		    # Contact's telephone numbers.
			if ($person["telephone"] != "000-000-0000") {
		        $vc->data['office_tel'] = $person["telephone"][0];
		    }
			$vc->data['home_tel'] = $person["homephone"][0];
			$vc->data['fax_tel'] = $person["fax"][0];

		    #Contact's email addresses
			$vc->data['email1'] = $person["email"][0];

			$result = send_email($_REQUEST['email'],"vCard from ".$inst_name." Directory",$vc->attach(),$contact_addy);
	        $send = true;
        }
	else {
		$error = true;
		$message = "The username or email address you supplied was not in a valid format.";
    }
    
	require "templates/$prefix/vcard.html";
}
else if (isset($_REQUEST["username"])) {
	require "templates/$prefix/vcard.html";
}
else {
   $error = true;
   $message = "You need to supply a valid username to use the vCard feature.";
   require "templates/$prefix/vcard.html";
}

$page->output();

function html_escape_person($person) {
    foreach($person as $att => $values) {
       if($att != "id") {         
         foreach($values as $index => $value) {
           $person[$att][$index] = ldap_decode(htmlentities($value));
         }
       }
    }
    return $person;
}

function ldap_decode($ldap_str) {
  return @preg_replace_callback("/0x(\d|[A-F]){4}/", "unicode2utf8", $ldap_str);
}

function send_email($to,$subject,$attachment,$contact_addy) {
    $attachment = chunk_split(base64_encode($attachment));
	$random_hash = md5(date("r", time())); 
	$headers = "From: ".$contact_addy."\r\nReply-To: ".$contact_addy; 
	$headers .= "\nMIME-Version: 1.0";
	$headers .= "\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
	$message = "This is a multi-part message in MIME format.

--PHP-mixed-".$random_hash." 

Find attached the vCard you wanted.

--PHP-mixed-".$random_hash."  
Content-type: text/directory; name=\"vcard.vcf\"  
Content-Transfer-Encoding: base64 
Content-Disposition: attachment  

".$attachment."
 
--PHP-mixed-".$random_hash."-- 

";
	//send the email 
	$mail_sent = @mail($to,$subject,$message,$headers); 
	return $mail_sent ? "success" : "failure"; 
}

?>
