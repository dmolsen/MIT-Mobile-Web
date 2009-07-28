<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */


require "../../lib/mit_ldap.php";
require "../page_builder/page_header.php";
require "../../config.gen.inc.php";


if($search_terms = $_REQUEST["a"]) {
} else {
  $search_terms = "";
}

$uid = $_REQUEST["uid"]; # UID provided by TextMarks to uniquely identify phone
$rightNow = date("YmdHis");
$selected = false;

if (isset($_REQUEST["username"])) {
   $person = lookup_username($_REQUEST["username"]);
   $person = html_escape_person($person);
   require "$prefix/sms/detail.html";
} elseif ($search_terms) {

   //search mit ldap directory
   if (preg_match("/([1-5]{1})/",$search_terms)) {
	$selected = true;
	$select = $search_terms;
    $db = db::$connection;
	$stmt = $db->prepare("SELECT searchterm FROM SMSDirectoryState WHERE uid = ? AND (350 + CAST(timestamp AS INT)) >= CAST(? AS INT) ORDER BY CAST(timestamp AS INT)");

    if (db::$use_sqlite) {
        $stmt->bindParam(1, $uid, PDO::PARAM_STR, 12);
        $stmt->bindParam(2, $rightNow, PDO::PARAM_STR, 12);
		$stmt->execute();
        $stmt->bindColumn(1,$search_terms);
	}
	else {
		$stmt->bind_param('ss', $uid, $rightNow);
	    $stmt->bind_result($search_terms);
	    $stmt->execute();
   }
   $people = mit_search($search_terms);
   $people = html_escape_people($people);

   $total = count($people);
   
   if($total==0) {
     $failed_search = True;
     require "$prefix/sms/index.html";
   } elseif($total==1) {
       $person = $people[0];
       require "$prefix/sms/detail.html";
   } else {
	
	   if ($selected == true) {
		 $person = $people[(int)$select];
	     require "$prefix/sms/detail.html";
	   }
	   else {
		$db = db::$connection;
		$stmt = $db->prepare("INSERT INTO SMSDirectoryState (searchterm, timestamp, uid) values (?, ?, ?, ?, ?, ?)");
	    if (db::$use_sqlite) {
	       $stmt->execute(array($search_terms, $rightNow, $uid));
	    }
	    else {
           $stmt->bind_param('sss', $search_terms, $rightNow, $uid);
	       $stmt->execute();
	    }

	    require "$prefix/sms/results.html";
	   } 
   }
} else {
   $page->cache();
   require "$prefix/sms/index.html";
}

#$page->output();

function detail_url($person) {
    return $_SERVER['SCRIPT_NAME'] . '?username=' . urlencode($person["id"]) . '&filter=' . urlencode($_REQUEST['filter']);
}

function phoneHREF($number) {
  return 'tel:1' . str_replace('-', '', $number);
}

function mailHREF($email) {
  return "mailto:$email";
}

function mapHREF($place) {
  preg_match("/^[A-Z]*\d+[A-Z]*/", $place, $match);
  return "../map/detail.php?selectvalues=" .  $match[0];
}

function html_escape_people($people) {
  foreach($people as $index => $person) {
    $people[$index] = html_escape_person($person);
  }
  return $people;
}

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


function has_phone($person) {
   return (count($person['homephone']) > 0) || 
          (count($person['telephone']) > 0) ||
          (count($person['fax']) > 0); 
}

function ldap_decode($ldap_str) {
  return preg_replace_callback("/0x(\d|[A-F]){4}/", "unicode2utf8", $ldap_str);
}


function unicode2utf8($match_array)
{
  $c = hexdec($match_array[0]);

  if($c < 0x80)
    {
      return chr($c);
    }
  else if($c < 0x800)
    {
      return chr( 0xc0 | ($c >> 6) ).chr( 0x80 | ($c & 0x3f) );
    }
  else if($c < 0x10000)
    {
      return chr( 0xe0 | ($c >> 12) ).chr( 0x80 | (($c >> 6) & 0x3f) ).chr( 0x80 | ($c & 0x3f) );
    }
  else if($c < 0x200000)
    {
      return chr(0xf0 | ($c >> 18)).chr(0x80 | (($c >> 12) & 0x3f)).chr(0x80 | (($c >> 6) & 0x3f)).chr(0x80 | ($c & 0x3f));
    }
  return false;
}

?>
