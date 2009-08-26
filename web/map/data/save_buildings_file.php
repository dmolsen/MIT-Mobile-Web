<?

require_once "../../../lib/db.php";
											
$db = db::$connection;
$stmt = $db->prepare("INSERT INTO Buildings (campus,latitude,longitude,name,physical_address,type,subtype,phone,wifi,website,hours,parent,code) values (?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?)");

$filename = "buildings.txt";
$fd = fopen ($filename, "r");
$contents = fread ($fd,filesize ($filename));
fclose ($fd); 

$lines = explode("\r", $contents);
foreach ($lines as $line) {
	$fields = explode("\t", $line);
	if (db::$use_sqlite) {
		$stmt->execute(array($fields[10], $fields[8], $fields[9], $fields[0], $fields[11], $fields[6], $fields[7], $fields[3], $fields[4], $fields[2], $fields[1],$fields[5], $fields[12]));
	}
	else {
		$stmt->bind_param('ssssssssssss', $fields[10], $fields[8], $fields[9], $fields[0], $fields[11], $fields[6], $fields[7], $fields[3], $fields[4], $fields[2], $fields[1],$fields[5], $fields[12]);
		$stmt->execute();
	}
}

?>
