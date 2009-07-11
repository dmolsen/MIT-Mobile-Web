<?php
/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class db {
  public static $connection = NULL;

  private static $host = 'localhost';
  private static $username = 'username';
  private static $passwd = 'passwd';
  private static $db = 'db';

  public static function init() {
    if(!self::$connection) {
      self::$connection = new PDO('sqlite:/path/to/MIT-Mobile-Web/db/development.sqlite3');
      #self::$connection = new mysqli(self::$host, self::$username, self::$passwd, self::$db);
    }
  }
}

db::init();

?>
