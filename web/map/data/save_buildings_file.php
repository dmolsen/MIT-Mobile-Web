<?php

require_once('../../../config.gen.inc.php');
require_once "MDB2.php";
require_once $install_path."lib/db.php";

$db = new db;

$stmtd =& $db->connection->prepare("DELETE FROM Buildings");
$stmtd->execute();

$filename = "buildings.txt";
$fd = fopen ($filename, "r");
$contents = fread ($fd,filesize ($filename));
fclose ($fd); 

$lines = explode("\n", $contents);
echo("Starting import of ".count($lines)." building records...");
foreach ($lines as $line) {
	$fields = explode("\t", $line);
	echo("\nImporting '".$fields[0]."'...");
	$types = array('text','text','text','text','text','text','text','integer','text','text','text','text','text');
	$stmt =& $db->connection->prepare("INSERT INTO Buildings (name,latitude,longitude,physical_address,type,subtype,code,parent,wifi,phone,website,hours,campus,uid) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$types);
	$stmt->execute(array($fields[0], $fields[1], $fields[2], $fields[3], $fields[4], $fields[5], $fields[6], $fields[7], $fields[8], $fields[9], $fields[10],$fields[11],$fields[12],$fields[13]));		
}
echo("\nCompleted importing building data...\n");

?>
