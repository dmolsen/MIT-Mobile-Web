<?

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

function detail_url($person) {
    return '/people/?username=' . urlencode($person["id"]) . '&filter=' . urlencode($_REQUEST['filter']);
}

function phoneHREF($number) {
  return 'tel:1' . str_replace('-', '', $number);
}

function mailHREF($email) {
  return "mailto:$email";
}

function mapHREF($where) {
  	$db = new db;
	$sql = "SELECT * FROM Buildings WHERE type != 'Parking Lot' OR type != 'Public Parking' GROUP BY name ORDER BY name ASC";
	$stmt = $db->connection->prepare($sql);
	$stmt->execute();
	$results = $stmt->fetchAll();
	
	foreach ($results as $result) {
		if (preg_match('/'.$result['name'].'/i',$where)) {
			return "<a href='/map/detail.php?loc=".$result['id']."&lat=".$result['latitude']."&long=".$result['longitude']."&maptype=roadmap'>";
		}
	}
	
	return $where;
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