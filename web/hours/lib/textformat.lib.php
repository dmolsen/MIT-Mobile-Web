<?

function categoryURL($category=NULL) {
	$category = $category ? $category : $_REQUEST['category'];
	return "?category=$category";
}

?>