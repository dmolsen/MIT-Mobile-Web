<?php

function getData($where=false) {	
	$db = new db;
	if ($where) {
		$stmt = $db->connection->prepare("SELECT * FROM Buildings WHERE ".$where." GROUP BY name ORDER BY name ASC");
	} else {
		$stmt = $db->connection->prepare("SELECT * FROM Buildings GROUP BY name ORDER BY name ASC");
	}
	$result = $stmt->execute();
	$results = $result->fetchAll();
	return $results;
}

?>