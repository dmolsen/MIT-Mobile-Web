<?

require_once "../../../lib/db.php";
											
$db = db::$connection;
$stmt = $db->prepare("INSERT INTO Buildings (campus,latitude,longitude,name,physical_address,type) values (?, ?, ?, ?, ?, ?)");	
						
foreach($buildings as $building_name => $building) {
	if (db::$use_sqlite) {
		$stmt->execute(array($building[3], $building[1], $building[2], $building_name, $building[4], $building[0]));
	}
	else {
		$stmt->bind_param('ssssss', $building[3], $building[1], $building[2], $building_name, $building[4], $building[0]);
		$stmt->execute();
	}
}

$filename = "buildings2.txt";
$fd = fopen ($filename, "r");
$contents = fread ($fd,filesize ($filename));
fclose ($fd); 

$lines = explode("\r", $contents);
foreach ($lines as $line) {
	$fields = explode("\t", $line);
	echo("name: ".$fields[0]." hours: ".$fields[1]." website: ".$fields[2]." phone: ".$fields[3]." wifi: ".$fields[4]." type: ".$fields[5]." subtype: ".$fields[6]." lat: ".$fields[7]." long: ".$fields[8]." campus: ".$fields[9]." addy: ".$fields[10]);
        echo("<br />");
        #echo($line."<br />");
}

?>
