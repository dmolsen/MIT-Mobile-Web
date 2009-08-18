<?

require_once "../../../lib/db.php";
											
$db = db::$connection;
$stmt = $db->prepare("INSERT INTO Buildings (campus,latitude,longitude,name,physical_address,type,subtype,phone,wifi,website,hours) values (?, ?, ?, ?, ?, ?)");	
						
foreach($buildings as $building_name => $building) {

}

$filename = "buildings2.txt";
$fd = fopen ($filename, "r");
$contents = fread ($fd,filesize ($filename));
fclose ($fd); 

$lines = explode("\r", $contents);
foreach ($lines as $line) {
	$fields = explode("\t", $line);
	if (db::$use_sqlite) {
		$stmt->execute(array($fields[9], $fields[7], $fields[8], $fields[0], $fields[10], $fields[5], $fields[6], $fields[3], $fields[4], $fields[2], $fields[1]));
	}
	else {
		$stmt->bind_param('sssssssssss', $fields[9], $fields[7], $fields[8], $fields[0], $fields[10], $fields[5], $fields[6], $fields[3], $fields[4], $fields[2], $fields[1]);
		$stmt->execute();
	}
}

?>
