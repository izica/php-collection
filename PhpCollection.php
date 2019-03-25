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
    public function only($keys){
        $fnMap = function ($arItem) use ($keys) {
            $arNewItem = [];
            foreach ($keys as $key){
                $arNewItem[$key] = $arItem[$key];
            }
            return $arNewItem;
        };

        return $this->map($fnMap);
    }

    /**
     * @param $keys
     * @return PhpCollection
     */
    public function exclude($keys){
        $arKeys = [];
        foreach ($keys as $key){
            $arKeys[$key] = $key;
        }

        $fnMap = function ($arItem) use ($arKeys) {
            $arNewItem = [];
            foreach ($arItem as $sKey => $anyValue){
                if(!isset($arKeys[$sKey])){
                    $arNewItem[$sKey] = $arItem[$sKey];
                }
            }
            return $arNewItem;
        };

        return $this->map($fnMap);
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
}
