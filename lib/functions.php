<?php
/*
 * Add a trailing slash to the specified path if it doesn't have one.
 * Based on the trailingslashit function from WordPress.
 */
function add_trailing_slash($path) {
	return remove_trailing_slash($path) . '/';
}

/*
 * Removes a trailing slash from the specified path if it has one.
 * Based on the untrailingslashit function from WordPress.
 */
function remove_trailing_slash($path) {
	return rtrim($path, '/');
}
?>
