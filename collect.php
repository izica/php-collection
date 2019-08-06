<?
require_once 'PhpCollection.php';

if (!function_exists('collect')) {
	function collect($data, $isSingleElement = false)
	{
		return \Izica\PhpCollection::collect($data, $isSingleElement);
	}
}
