<?
require_once 'PhpCollection.php';

if (!function_exists('collect')) {
	function collect($data)
	{
		return \Izica\PhpCollection::collect($data);
	}
}
