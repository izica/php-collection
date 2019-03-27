# Php collection

inspired by Illuminate\Support\Collection and Lodash

### Usage
```
composer require izica/php-collection
```

### Notice
Every method create new collection, not mutate data;

```php

$collection = PhpCollection:collect([100, 200, 300, 400]);
$collection2 = $collection->filter(function($item){
    return $item > 200;
})

/*
    $collection != $collection2
*/

```

### Documentation

* [collect](#collectarray)
* [implode OR join](#implodearray----serializer---or-joinarray----serializer--)
* [pluck](#pluckkey)
* [only](#onlykeys)
* [exclude](#excludekeys)
* [filter](#filterfunctionitem)
* [map](#mapfunctionitem)
* [keyBy](#keybykey)
* [groupBy](#groupbykey)
* [find](#findfunctionitem)
* [some OR contains](#somefunctionitem-or-containsfunctionitem)
* [every](#everyfunctionitem)
* [sort](#sortfunctionitem)
* [sortBy](#sortbykey)
* [sortWithKeys](#sortwithkeysfunctionitem)
* [values](#values)
* [first](#first)
* [last](#last)
* [count](#count)
* [all or toArray](#all-or-toarray)
* [toJson](#tojson)


#### collect($array)
```php
$products = [
    ['id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products);

```

#### implode($array = ', ', $serializer = '') OR join($array = ', ', $serializer = '')

```php
$collection = PhpCollection:collect([100, 'data', 300, 400])->implode();
/*
    100, data, 300, 400
*/

$products = [
    ['id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
];

// should return string
$serializer = function($item){
    return "{$item['name']}-{$item['price']}$"; // or for example -- return json_encode($item);
};

$collection = PhpCollection:collect($products)->implode(', ', $serializer);
/*
    product 1-100$, product 2-200$, product 3-300$
*/
```

#### pluck($key)
```php
$products = [
    ['id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products)->pluck('id')->all();
/*
    [1, 2, 3]
*/  

$collection = PhpCollection:collect($products)->pluck('name')->all();
/*
    ['product 1', 'product 2', 'product 3']
*/ 
```

#### only($keys)
```php
$products = [
    ['id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products)->only(['id', 'name'])->all();
/*
[
    ['id' => 1, 'name' => 'product 1'],
    ['id' => 2, 'name' => 'product 2'],
    ['id' => 3, 'name' => 'product 3']
]
*/

$collection = PhpCollection:collect($products)->only(['id', 'name' => 'title'])->all();

/*
[
    ['id' => 1, 'title' => 'product 1'],
    ['id' => 2, 'title' => 'product 2'],
    ['id' => 3, 'title' => 'product 3']
]
*/

```

#### exclude($keys)
```php
$products = [
    ['id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products)->exclude(['name'])->all();

/*
[
    ['id' => 1, 'price' => 100],
    ['id' => 2, 'price' => 200],
    ['id' => 3, 'price' => 300]
]
*/

```

#### filter(function($item))
```php
$products = [
    ['id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products)
    ->filter(function($item){
        return $item['price'] > 100    
    })
    ->all();

/*
[
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
]
*/

```

#### map(function($item))
```php
$products = [
    ['id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 3, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products)
    ->map(function($item){
        $item['pricex2'] = $item['price'] * 2;
        return $item;   
    })
    ->all();

/*
[
    ['id' => 1, 'name' => 'product 1', 'price' => 100, 'pricex2' => 200],
    ['id' => 2, 'name' => 'product 2', 'price' => 200, 'pricex2' => 400],
    ['id' => 3, 'name' => 'product 3', 'price' => 300, 'pricex2' => 600]
]
*/

```

#### keyBy($key)
```php
$products = [
    ['id' => 16, 'name' => 'product 1', 'price' => 100],
    ['id' => 22, 'name' => 'product 2', 'price' => 200],
    ['id' => 31, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products)->keyBy('id')->all();

/*
[
    16 => ['id' => 1, 'name' => 'product 1', 'price' => 100, 'pricex2' => 200],
    22 => ['id' => 2, 'name' => 'product 2', 'price' => 200, 'pricex2' => 400],
    31 => ['id' => 3, 'name' => 'product 3', 'price' => 300, 'pricex2' => 600]
]
*/

```

#### groupBy($key)
```php
$products = [
    ['id' => 16, 'category_id' => 1, 'name' => 'product 1', 'price' => 100],
    ['id' => 22, 'category_id' => 2, 'name' => 'product 2', 'price' => 200],
    ['id' => 31, 'category_id' => 2, 'name' => 'product 3', 'price' => 300]
];

$collection = PhpCollection:collect($products)->groupBy('category_id')->all();

/*
[
    1 => [
        ['id' => 16, 'category_id' => 1, 'name' => 'product 1', 'price' => 100]
    ],
    2 => [
        ['id' => 22, 'category_id' => 2, 'name' => 'product 2', 'price' => 200],
        ['id' => 31, 'category_id' => 2, 'name' => 'product 3', 'price' => 300]
    ]   
]
*/

```

#### find(function($item)) 
#### some(function($item)) OR contains(function($item))
#### every(function($item))
#### sort(function($item))
#### sortBy($key)
#### sortWithKeys(function($item))
#### values()
#### first()
#### last()
#### count()
#### all() OR toArray()
#### toJson()
#### zip()

## TODO
#### dumpBrowser()
#### dump()
#### toCsv()
#### toXml()
