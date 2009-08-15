<?

require_once "../../../lib/db.php";
require_once "buildings.import.inc.php";
											
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
	
?>
