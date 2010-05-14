<?

require_once('../../../config.gen.inc.php');
require_once('../../../lib/db.php');
											

$db = new db;

$stmtd = $db->connection->prepare("DELETE FROM Buildings");
$stmtd->execute();

$stmt = $db->connection->prepare("INSERT INTO Buildings (name,latitude,longitude,physical_address,type,subtype,code,parent,wifi,phone,website,campus,hours) values (?,?,?,?,?,?,?,?,?,?,?,?,?)");

$filename = "buildings.txt";
$fd = fopen ($filename, "r");
$contents = fread ($fd,filesize ($filename));
fclose ($fd); 

$lines = explode("\r", $contents);
echo(count($lines));
foreach ($lines as $line) {
	$fields = explode("\t", $line);
	if ($db_use_sqlite) {
		$stmt->execute(array($fields[0], $fields[1], $fields[2], $fields[3], $fields[4], $fields[5], $fields[6], $fields[7], $fields[8], $fields[9], $fields[10],$fields[11],$fields[12]));
	}
	else {
		$stmt->bind_param('ssssssssssss', $fields[0], $fields[1], $fields[2], $fields[3], $fields[4], $fields[5], $fields[6], $fields[7], $fields[8], $fields[9], $fields[10], $fields[11],$fields[12]);
		$stmt->execute();
	}
}

?>
