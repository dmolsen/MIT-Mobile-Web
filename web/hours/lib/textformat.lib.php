<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

function categoryURL($category=NULL) {
	$category = $category ? $category : $_REQUEST['category'];
	return "?category=$category";
}

?>