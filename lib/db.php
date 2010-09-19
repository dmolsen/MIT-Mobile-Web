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

global $db_use_sqlite,$sqlite_path,$db_host,$db_username,$db_passwd,$db_name;

class db {
  
  public static $connection,$use_sqlite;
  private static $path,$host,$username,$passwd,$db;

  public function __construct(){
	
	global $db_use_sqlite,$sqlite_path,$db_host,$db_username,$db_passwd,$db_name;
	
	$this->connection = false;
	$this->use_sqlite = $db_use_sqlite;
	$this->path = $sqlite_path;
	$this->host = $db_host;
	$this->username = $db_username;
	$this->passwd = $db_passwd;
	$this->db = $db_name;
	
	if(!$this->connection) {
		if ($this->use_sqlite) {
			$this->connection = new PDO('sqlite:'.$this->path);
		} else {
			$this->connection = new mysqli($this->host, $this->username, $this->passwd, $this->db);	
		}
	}
  }
}

?>
