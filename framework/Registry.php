<?php

namespace Framework;

/**
 * Set and get data from registry (using registry pattern)
 * return array
 */
class Registry
{

    private static $_instances = array();

    private function __construct()
    {
        // do nothing
    }

    private function __clone()
    {
        // do nothing
    }

    public static function get($key, $default = NULL)
    {
        if (isset(self::$_instances[$key]))
        {
            return self::$_instances[$key];
        }
        return $default;
    }

    public static function set($key, $instance = NULL)
    {
        self::$_instances[$key] = $instance;
    }

    public static function erase($key)
    {
        unset(self::$_instances[$key]);
    }

}