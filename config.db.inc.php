<?php

/**
 * Copyright (c) 2008 Massachusetts Institute of Technology
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* Quick Database Configuration File for MIT Mobile Web OSP */

/* MySQL or SQLite? */
$use_sqlite  = true;  # if using mysql change this to false, addresses naming issue between PDO & MySQL functions

/* SQLite Config */
$sqlite_path = "/apache/htdocs/MIT-Mobile-Web/db/development.sqlite3"; # file system path to your SQLite database

/* MySQL Config Info */
$host        = 'localhost';
$username    = 'username';
$passwd      = 'passwd';
$db          = 'db';


?>