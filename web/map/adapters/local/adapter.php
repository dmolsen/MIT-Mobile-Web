<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

class MapAdapter extends ModuleAdapter {  
	
	// standardized method to perform the SQL call
	private static function getPlaces($sql) {
		$db = new db;
		$db->connection->setFetchMode(MDB2_FETCHMODE_ASSOC);
		$stmt =& $db->connection->prepare($sql);
	    $result = $stmt->execute();
	    $places = $result->fetchAll();
		return $places;
	}
	
	// build out the sub-SQL string based on which drilldown parameter was sent (e.g. A or N-Q)
	private static function subSQLStrBuilder($drilldown,$type) {
		if (stristr($drilldown,"-")) {
		    $drilldown_a = explode('-',$drilldown);
			$alpha_a = array("1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
			$i = array_search($drilldown_a[0], $alpha_a);
			$max = array_search($drilldown_a[1], $alpha_a);
			while ($i <= $max) {
				$sql_str .= $type." LIKE \"".$alpha_a[$i]."%\"";
				if ($i < ($max)) { $sql_str .= " OR "; }
				$i++;
			}
	    }
	    else {
			$sql_str = $type." LIKE \"".$drilldown."%\"";
	    }
		return $sql_str;
	}
	
	// from the Categories list figure out which types in the database to search against (e.g. Building or Dining)
	private static function getSearchableCategories() {
		$or = "";
		$sql_str = "(";
		
		# include the list of 'types' from the database that should be searched
		include("searchabletypes.inc.php");
	
		foreach ($searchable_types as $searchable_type) {	
			$sql_str .= $or."type = '".$searchable_type."' ";
			if ($or == "") {
				$or = "OR ";
			}
		}
		$sql_str .= ")";
		return $sql_str;
	}
	
	// match the key names returned from the database with the keys used in the templates
	private static function convertPlaces($places) {	
		$convertedPlaces = array();
		foreach ($places as $place) {
			
			// this is sort of redundant just because it'll match what we already have in the database
			$convertedPlaces[] = array('id' => $place['id'],
									'campus' => $place['campus'],
									'code' => $place['code'],
									'hours' => $place['hours'],
									'latitude' => $place['latitude'],
									'longitude' => $place['longitude'],
									'name' => $place['name'],
									'parent' => $place['parent'],
									'phone' => $place['phone'],
									'physical_address' => $place['physical_address'],
									'subtype' => $place['subtype'],
									'type' => $place['type'],
									'uid' => $place['uid'],
									'website' => $place['website'],
									'wifi' => $place['wifi']);
		}
		
		return $convertedPlaces;
	}
	
	// get information for a specific location
	public static function getPlace($location) {
		$sql = "SELECT * FROM Buildings WHERE id = ".$location;
		$places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
	
	// get information for a specific locations parent using the UID
	public static function getParent($location) {
		$sql = "SELECT * FROM Buildings WHERE uid = ".$location;
		$places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
	
	// get a list of places by campus - this is WVU-specific
	public static function getPlacesByCampus($drilldown) {
		$where = self::getSearchableCategories()." and campus = '".$drilldown."'";
		$sql = "SELECT * FROM Buildings WHERE ".$where." GROUP BY name ORDER BY name ASC";
		$places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
	
	// get a list of places by building code
	public static function getPlacesByCode($drilldown) {
		$sql_substr = self::subSQLStrBuilder($drilldown,'code');
		$sql = "SELECT * FROM Buildings WHERE ".$sql_substr." GROUP BY code ORDER BY code ASC";
	    $places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
	
	// get a list of places by location name
	public static function getPlacesByName($drilldown) {
		$sql_substr = self::subSQLStrBuilder($drilldown,'name');
		$where = self::getSearchableCategories()." and ".$sql_substr;
		$sql = "SELECT * FROM Buildings WHERE ".$where." GROUP BY name ORDER BY name ASC";
		$places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
	
	// get a list of places by type (e.g. Building or Library)
	public static function getPlacesByType($type) {
		$sql = "SELECT * FROM Buildings WHERE type = '".$type."' GROUP BY name ORDER BY name ASC";
		$places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
	
	// get a list of places that have WiFi
	public static function getPlacesByWiFi() {
		$sql = "SELECT * FROM Buildings WHERE wifi = 'Y' GROUP BY name ORDER BY name ASC";
		$places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
	
	// return a list of places that are searched via the query term
	public static function searchPlaces($search_terms) {
		$where = self::getSearchableCategories();
		$sql = "SELECT * FROM Buildings WHERE (name LIKE '%".$search_terms."%' OR physical_address LIKE '%".$search_terms."%' OR code LIKE '%".$search_terms."%') and ".$where." GROUP BY name ORDER BY name ASC";
		$places = self::getPlaces($sql);
		$convertedPlaces = self::convertPlaces($places);
		return $convertedPlaces;
	}
}

?>