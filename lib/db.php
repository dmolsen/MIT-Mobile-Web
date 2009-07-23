<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class db {
  
  require '../config.db.inc.php';

  public static function init() {
    if(!self::$connection) {
      if ($use_sqlite) {
        self::$connection = new PDO('sqlite:'.$sqlite_path);
      }
	  else {
		self::$connection = new mysqli(self::$host, self::$username, self::$passwd, self::$db);
	  }
    }
  }
}

db::init();

?>
