<?php

namespace Framework;

class ArrayMethods
{

    public static function trim($array)
    {
        return array_map(function ($item)
        {
            return trim($item);
        }, $array);
    }

    public static function clean($array)
    {
        return array_filter($array, function($item)
        {
            return !empty($item);
        });
    }

    public static function flatten($array, $return = array())
    {
        foreach ($array as $key => $value)
        {
            if (is_array($value) || is_object($value))
            {
                $return = self::flatten($value, $return);
            }
            else
            {
                $return[] = $value;
            }
        }

        return $return;
    }
    
    public static function first($array)
    {
        if (sizeof($array) == 0)
        {
            return NULL;
        }
        
        $keys = array_keys($array);
        return $array[$keys[0]];
    }

}