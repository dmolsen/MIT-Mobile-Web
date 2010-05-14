<?

/**
 * Copyright (c) 2009 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* I may have just cheap copied this from the index and as such it might have a lot of extra code
   that is not needed. Especially after line #90. Sorry. (dave o. Oct. 11, 2009) */

// various copy includes
require_once "../../../config.gen.inc.php";

// records stats
require_once "../../page_builder/page_header.php";

// libs
require_once "../../../lib/ldap_services.php";
require_once "../../../lib/db.php";

if($search_terms = $_REQUEST["a"]) { # 'a' is a request var from TextMarks
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
  
   
   $db = new db;

   # if a number comes back as a search term then a user had the option
   # to select people from a list. find the person that corresponds to the number
   if (preg_match("/(1|2|3|4|5)/",$search_terms)) {
        $selected = true;
		$select = $search_terms - 1;
	    $stmt = $db->connection->prepare("SELECT searchterm, timestamp FROM SMSDirState WHERE uid = ? AND (350 + CAST(timestamp AS INT)) >= CAST(? AS INT) ORDER BY CAST(timestamp AS INT) DESC LIMIT 1");

    	if (db::$use_sqlite) {
	        $stmt->bindParam(1, $uid, PDO::PARAM_STR, 12);
	        $stmt->bindParam(2, $rightNow, PDO::PARAM_STR, 12);
			$stmt->execute();
	        $stmt->bindColumn(1,$search_terms);
	        $stmt->bindColumn(2,$timestamp);
		}
		else {
			$stmt->bind_param('ss', $uid, $rightNow);
		    $stmt->bind_result($search_terms);
		    $stmt->execute();
	   }
	   if (!$stmt->fetch()) {
	        echo("no results returned.");
	        exit;
	   }
   }
   $people = mit_search($search_terms);
   $people = html_escape_people($people);

   $total = count($people);
   
   if($total==0) {
     $failed_search = True;
     require "templates/$prefix/sms/index.html";
   } elseif($total==1) {
       $person = $people[0];
       require "templates/$prefix/sms/detail.html";
   } else {
	   if ($selected == true) {
		 $person = $people[(int)$select];
	     require "templates/$prefix/sms/detail.html";
	   }
	   else {
         $stmt_1 = $db->prepare("INSERT INTO SMSDirState (searchterm,timestamp,uid) VALUES (?,?,?)");
	     if (db::$use_sqlite) {
	       $stmt_1->execute(array($search_terms,$rightNow,$uid));
	     }
	     else {
           $stmt_1->bind_param('sss', $search_terms, $rightNow, $uid);
	       $stmt_1->execute();
	     }
	     require "templates/$prefix/sms/results.html";
	  } 
   }
} else {
   $page->cache();
   require "templates/$prefix/sms/index.html";
}

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
