<?

# require the libs for json_encode
require_once "PEAR.php";
require_once "../../../lib/Services_JSON-1.0.2/JSON.php";

require_once "../data/data.inc.php";

// sets up google calendar classes
require "../lib/google_calendar.init.php";

$id = $_REQUEST['id'];
$maxresults = (int)$_REQUEST['max'];

$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar
$client = Zend_Gdata_ClientLogin::getHttpClient($username.'@gmail.com',$password,$service);
$gdataCal = new Zend_Gdata_Calendar($client);
$query = $gdataCal->newEventQuery();
$query->setUser($calendars[$id]['user']);
$query->setVisibility('private');
$query->setProjection('full');
$query->setOrderby('starttime');
$query->setSortorder('a');
$query->setmaxresults($maxresults);
$eventFeed = $gdataCal->getCalendarEventFeed($query);

$json_data = array();

foreach ($eventFeed as $event) { 
	
	$title = $event->title->text;
	$loc = briefLocation($event);
	$location = $loc->valueString;
	$description = $event->getContent()->text;
	$when = $event->getWhen();
	$startTime = strtotime($when[0]->startTime);
	$endTime = strtotime($when[0]->endTime);

	list($description,$event_link) = getExtraData($description,'link',true);
	list($description,$contact_phone) = getExtraData($description,'contact_phone',false);
	list($description,$contact_email) = getExtraData($description,'contact_email',false);
	list($description,$contact_name) = getExtraData($description,'contact_name',false);
	
	$event_link = trim($event_link);
	$contact_phone = trim($contact_phone);
	$contact_email = trim($contact_email);
	$contact_name = trim($contact_name);
	
	if ($event_link == "http://") {
		$event_link = "";
	}

	$json_data[] = array("title" => $title, "location" => $location, "description" => $description, "startTime" => $startTime, "endTime" => $endTime, "eventLink" => $event_link, "contactPhone" => $contact_phone, "contactEmail" => $contact_email, "contactName" => $contact_name);
}

$json = new Services_JSON();
echo($json->encodeUnsafe($json_data));

function getExtraData($description,$extra_data_pattern,$urlize) {
	$extra_data = '';
	$pattern = '/\[\['.$extra_data_pattern.'\]\](.*)\[\[\/'.$extra_data_pattern.'\]\]/';
	if (preg_match($pattern,$description,$matches)) {
		if (trim($matches[1] != '')) {
			if ($urlize) {
				$extra_data = URLize($matches[1]);
			} else {
				$extra_data = $matches[1];
			}
		}
		$description = preg_replace($pattern,'',$description);
	}
	return array($description,$extra_data);
}

function briefLocation($event) {
	$where = $event->getWhere();
	if ($where[0]) {
		return $where[0];
	} else {
		return false;
	}
}

function URLize($web_address) {
  if(preg_match('/^http\:\/\//', $web_address)) {
    return $web_address;
  } else {
    return 'http://' . $web_address;
  }
}
?>