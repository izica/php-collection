<?php

namespace Izica;

/**
 * Class PhpCollection
 */
class PhpCollection
{
    /**
     * @var array
     */
    private $arCollection = [];

    /**
     * PhpCollection constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->arCollection = $data;
    }

    /**
     * @param $data
     * @param $isSingleElement
     * @return PhpCollection
     */
    public static function collect($data, $isSingleElement = false)
    {
        if ($isSingleElement) {
            return new PhpCollection([$data]);
        }
        return new PhpCollection($data);
    }

    /**
     * @param $key
     * @return PhpCollection
     */
    public function pluck($key)
    {
        $arData = [];
        foreach ($this->arCollection as $arItem) {
            $arData[] = $arItem[$key];
        }
        return new PhpCollection($arData);
    }

    /**
     * @param $keys
     * @return PhpCollection
     */
    public function only($keys)
    {
        $arKeys = [];
        foreach ($keys as $anyKey => $sValue) {
            if (is_int($anyKey)) {
                $arKeys[$sValue] = $sValue;
            } else {
                $arKeys[$anyKey] = $sValue;
            }
        }

        $fnMapItem = function ($arItem) use ($arKeys) {
            $arNewItem = [];
            foreach ($arItem as $sKey => $anyValue) {
                if (isset($arKeys[$sKey])) {
                    $arNewItem[$arKeys[$sKey]] = $arItem[$sKey];
                }
            }
            return $arNewItem;
        };

        $arData = [];

        foreach ($this->arCollection as $sKey => $arItem) {
            $arData[$sKey] = $fnMapItem($arItem);
        }

        return new PhpCollection($arData);
    }

    /**
     * @param string[] $keys
     * @return PhpCollection
     */
    public function exclude($keys)
    {
        $arKeys = [];
        foreach ($keys as $key) {
            $arKeys[$key] = $key;
        }

        $fnMapItem = function ($arItem) use ($arKeys) {
            $arNewItem = [];
            foreach ($arItem as $sKey => $anyValue) {
                if (!isset($arKeys[$sKey])) {
                    $arNewItem[$sKey] = $arItem[$sKey];
                }
            }
            return $arNewItem;
        };

        $arData = [];

        foreach ($this->arCollection as $sKey => $arItem) {
            $arData[$sKey] = $fnMapItem($arItem);
        }

        return new PhpCollection($arData);
    }

    /**
     * @param $array
     * @return PhpCollection
     */
    public function zip($array)
    {
        $arData = [];

        $arCollection = array_values($this->arCollection);
        $nCount = count($arCollection);

        for ($nIndex = 0; $nIndex < $nCount; $nIndex++) {
            $arFirstItem = is_array($arCollection[$nIndex]) ? $arCollection[$nIndex] : [$arCollection[$nIndex]];
            $arSecondItem = is_array($array[$nIndex]) ? $array[$nIndex] : [$array[$nIndex]];
            $arData[] = array_merge($arFirstItem, $arSecondItem);
        }

        return new PhpCollection($arData);
    }

    /**
     * @return PhpCollection
     */
    public function keys()
    {
        return new PhpCollection(array_keys($this->arCollection));
    }

    /**
     * @param integer $count
     * @return PhpCollection
     */
    public function offset($count)
    {
        $this->arCollection = array_slice($this->arCollection, $count);
        return $this;
    }

    /**
     * @param integer $offset
     * @param integer $length
     * @return PhpCollection
     */
    public function slice($offset, $length)
    {
        $this->arCollection = array_slice($this->arCollection, $offset, $length);
        return $this;
    }

    /**
     * @param integer $length
     * @return PhpCollection
     */
    public function limit($length)
    {
        $this->arCollection = array_slice($this->arCollection, 0, $length);
        return $this;
    }

    /**
     * @param string $glue
     * @param string $serializer
     * @return string
     */
    public function implode($glue = ', ', $serializer = '')
    {
        $arData = [];
        if ($serializer === '') {
            foreach ($this->arCollection as $arItem) {
                $arData[] = $arItem;
            }
        } else {
            foreach ($this->arCollection as $arItem) {
                $arData[] = $serializer($arItem);
            }
        }

        return implode($glue, $arData);
    }

    /**
     * @param string $glue
     * @return string
     */
    public function join($glue = ', ')
    {
        return $this->implode($glue);
    }

    /**
     * @param bool $function
     * @return PhpCollection
     */
    public function filter($function = false)
    {
        if ($function === false) {
            $function = function ($item) {
                return true;
            };
        }
        $arData = [];
        foreach ($this->arCollection as $arItem) {
            if ($function($arItem)) {
                $arData[] = $arItem;
            }
        }
        return new PhpCollection($arData);
    }

    /**
     * @param bool $function
     * @return PhpCollection
     */
    public function map($function = false)
    {
        if ($function === false) {
            $function = function ($item) {
                return $item;
            };
        }
        $arData = [];
        foreach ($this->arCollection as $sKey => $arItem) {
            $arData[$sKey] = $function($arItem, $sKey);
        }
        return new PhpCollection($arData);
    }

    /**
     * @param $key
     * @return PhpCollection
     */
    public function keyBy($keyOrFunc)
    {
        $arData = [];
        if (is_callable($keyOrFunc)) {
            foreach ($this->arCollection as $arItem) {
                $arData[$keyOrFunc($arItem)] = $arItem;
            }
        } else {
            foreach ($this->arCollection as $arItem) {
                $arData[$arItem[$keyOrFunc]] = $arItem;
            }
        }

        return new PhpCollection($arData);
    }

    /**
     * @param $key
     * @return PhpCollection
     */
    public function groupBy($key)
    {
        $arData = [];
        foreach ($this->arCollection as $arItem) {
            $arData[$arItem[$key]][] = $arItem;
        }
        return new PhpCollection($arData);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->arCollection;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->arCollection);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->arCollection);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        foreach ($this->arCollection as $arItem) {
            return $arItem;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        $arData = false;

        foreach ($this->arCollection as $arItem) {
            $arData = $arItem;
        }

        return $arData;
    }

    /**
     * @return PhpCollection
     */
    public function values()
    {
        $arData = array_values($this->arCollection);
        return new PhpCollection($arData);
    }

    /**
     * @param $key
     * @param bool $asc
     * @return PhpCollection
     */
    public function sortBy($key, $asc = true)
    {
        $arData = array_values($this->arCollection);
        if ($asc) {
            usort($arData, function ($arItemA, $arItemB) use ($key) {
                return ($arItemA[$key] > $arItemB[$key]) ? +1 : -1;
            });
        } else {
            usort($arData, function ($arItemA, $arItemB) use ($key) {
                return ($arItemA[$key] > $arItemB[$key]) ? -1 : +1;
            });
        }
        return new PhpCollection($arData);
    }

    /**
     * @param $function
     * @return PhpCollection
     */
    public function sort($function = false)
    {
        if ($function === false) {
            $function = function ($a, $b) {
                return $a > $b ? +1 : -1;
            };
        }
        $arData = $this->arCollection;
        uasort($arData, $function);
        return new PhpCollection($arData);
    }

    /**
     * @param $function
     * @return bool|mixed
     */
    public function find($function)
    {
        $arData = [];
        foreach ($this->arCollection as $arItem) {
            if ($function($arItem)) {
                return $arItem;
            }
        }
        return false;
    }

    /**
     * @param $function
     * @return bool
     */
    public function every($function)
    {
        foreach ($this->arCollection as $arItem) {
            if (!$function($arItem)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $function
     * @return bool
     */
    public function contains($function)
    {
        foreach ($this->arCollection as $arItem) {
            if ($function($arItem)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $function
     * @return bool
     */
    public function some($function)
    {
        return $this->contains($function);
    }

    /**
     * @param $array
     * @return PhpCollection
     */
    public function merge($array)
    {
        return new PhpCollection(array_merge($this->arCollection, $array));
    }

    /**
     * @param $element
     * @return PhpCollection
     */
    public function push($element)
    {
        return new PhpCollection(array_merge($this->arCollection, [$element]));
    }

    /**
     * @param $element
     * @return PhpCollection
     */
    public function add($element)
    {
        return $this->push($element);
    }


    /**
     * @param $element
     * @return PhpCollection
     */
    public function append($element)
    {
        return $this->push($element);
    }

    /**
     * @param $element
     * @return PhpCollection
     */
    public function prepend($element)
    {
        return new PhpCollection(array_merge([$element], $this->arCollection));
    }

    /**
     * @return PhpCollection
     */
    public function unique()
    {
        $arBuffer = [];
        $arResult = [];
        foreach ($this->arCollection as $arItem) {
            $sJson = json_encode($arItem);
            $sHash = md5($sJson);
            if (isset($arBuffer[$sHash])) {
                if ($arBuffer[$sHash] !== $sJson) {
                    $arResult[] = $arItem;
                }
            } else {
                $arBuffer[$sHash] = $sJson;
                $arResult[] = $arItem;
            }
        }

        return new PhpCollection($arResult);
    }

    /**
     * @return number
     */
    public function sum($keyOrFunc = null)
    {
        $nResult = 0;
        if ($sKey === null) {
            foreach ($this->arCollection as $nItem) {
                $nResult += $nItem;
            }
        } else {
            if (is_callable($keyOrFunc)) {
                foreach ($this->arCollection as $arItem) {
                    $nResult += $keyOrFunc($arItem);
                }
            } else {
                foreach ($this->arCollection as $arItem) {
                    $nResult += $arItem[$sKey];
                }
            }
        }

        return $nResult;
    }
}
