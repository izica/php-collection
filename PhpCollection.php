<?php

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
     * @return PhpCollection
     */
    public static function collect($data)
    {
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
        foreach ($keys as $key) {
            $arKeys[$key] = $key;
        }

        $fnMapItem = function ($arItem) use ($arKeys) {
            $arNewItem = [];
            foreach ($arItem as $sKey => $anyValue) {
                if (isset($arKeys[$sKey])) {
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
     * @param $keys
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
        foreach ($this->arCollection as $arItem) {
            $arData[] = $function($arItem);
        }
        return new PhpCollection($arData);
    }

    /**
     * @param $key
     * @return PhpCollection
     */
    public function keyBy($key)
    {
        $arData = [];
        foreach ($this->arCollection as $arItem) {
            $arData[$arItem[$key]] = $arItem;
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
    }

    /**
     * @return mixed
     */
    public function last()
    {
        $arData = '';

        foreach ($this->arCollection as $arItem) {
            $arData = $arItem;
        }
        return $arItem;
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
     * @return array
     */
    public function sortBy($key)
    {
        $arData = array_values($this->arCollection);
        usort($arData, function ($arItemA, $arItemB) use ($key) {
            return ($arItemA[$key] > $arItemB[$key]) ? +1 : -1;
        });
        return $arData;
    }

    /**
     * @param $function
     * @return array
     */
    public function sort($function)
    {
        $arData = array_values($this->arCollection);
        usort($arData, $function);
        return $arData;
    }

    /**
     * @param $function
     * @return array
     */
    public function sortWithKeys($function)
    {
        $arData = $this->arCollection;
        uasort($arData, $function);
        return $arData;
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
}
