# Php collection

inspired by Illuminate\Support\Collection and Lodash

## Description (TODO)

```php
	$arProductIds = PhpCollection::collect($arProducts)->pluck('id')->all();

```

* collect($array) -static
* implode($glue, $serializer)
* join -implode alias
* pluck($key)
* only($keys)
* exclude($keys)
* filter(function($item))
* map(function($item))
* keyBy($key)
* groupBy($key)
* toArray()
* all -toArray alias
* toJson()
* TODO