<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

// hack to make sure config doesn't load twice
if (!isset($db_use_sqlite)) {
	require_once('../../config.gen.inc.php');
}

global $db_use_sqlite,$sqlite_path,$db_host,$db_port,$db_username,$db_passwd,$db_name;

class db {
  
  public static $connection;

  public function __construct(){
	
	global $db_use_sqlite,$sqlite_path,$db_type,$db_host,$db_port,$db_username,$db_passwd,$db_name;
	
	$this->connection = false;
	
	// the pdoSqlite function doesn't actually work at this point because it
	// doesn't support SQLite3 which is what I had originally used
	if ($db_use_sqlite) {
		$dsn = array(
		    'phptype'  => 'pdoSqlite',
		    'database' => $sqlite_path,
		    'mode'     => '0777',
		);
	} else {
		$dsn = array(
		    'phptype'  => $db_type,
		    'username' => $db_username,
		    'password' => $db_passwd,
		    'hostspec' => $db_host,
		    'port'     => $db_port,
		    'database' => $db_name
		);
	}

	$options = array(
	    'debug'       => 2,
	    'portability' => MDB2_PORTABILITY_ALL,
	);
	
	// uses MDB2::factory() to create the instance
	// and also attempts to connect to the host
	$this->connection =& MDB2::connect($dsn, $options);
	if (PEAR::isError($this->connection)) {
	    die($this->connection->getMessage().','.$this->connection->getDebugInfo());
	}
  }
}

?>
