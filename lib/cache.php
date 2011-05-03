<?php
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Cache');

function mwosp_get_cache() {
	global $cache_frontend, $cache_frontend_options, $cache_backend, $cache_backend_options;

	$cache = Zend_Cache::factory(
		$cache_frontend,
		$cache_backend,
		$cache_frontend_options,
		$cache_backend_options
	);

	return $cache;
}
?>