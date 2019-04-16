<?
require_once 'PhpCollection.php';

if (!function_exists('collect')) {
	function collect($data)
	{
		return PhpCollection::collect($data);
	}
}
