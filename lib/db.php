<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class db {
  
  /* MySQL or SQLite */
  public static $use_sqlite   = true;  # if using mysql change this to false, addresses naming issue between PDO & MySQL functions
  public static $connection   = NULL;

  /* SQLite Config */
  private static $sqlite_path = "/path/to/install/db/development.sqlite3"; # file system path to your SQLite database

  /* MySQL Config Info */
  private static $host        = 'localhost';
  private static $username    = 'username';
  private static $passwd      = 'passwd';
  private static $db          = 'db';

  public static function init() {
    if(!self::$connection) {
      if (self::$use_sqlite) {
        self::$connection = new PDO('sqlite:'.self::$sqlite_path);
      }
	  else {
		self::$connection = new mysqli(self::$host, self::$username, self::$passwd, self::$db);
	  }
    }
  }
}

db::init();

?>
