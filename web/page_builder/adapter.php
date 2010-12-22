<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

/* 
 * This class is meant to provide a method for figuring what adapter is needed to access data for a module
 */

# require the YAML loader
require_once "../../lib/spyc-0.4.5/spyc.php";

class ModuleAdapter {  
	
	// returns the adapter for the requesting module
	public static function find() {
		$base_dir = getcwd();
		$config_file = $base_dir."/info.yml";
		if (file_exists($config_file)) {
			$config = Spyc::YAMLLoad($config_file);
			return $config['adapter'];
		} else {
			return false;
		}
	}
	
}