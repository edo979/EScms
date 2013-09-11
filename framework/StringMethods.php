<?php

namespace Framework;

class StringMethods
{
    private static $_delimiter = "#";
    
    private static function normalize($pattern)
    {
        return self::$_delimiter.trim($pattern, self::$_delimiter).self::$_delimiter;
    }


    public static function match($string, $pattern)
    {
        preg_match_all(self::normalize($pattern), $string, $matches, PREG_PATTERN_ORDER);
        
        if(!empty($matches[1]))
        {
            return $matches[1];
        }
        if(!empty($matches[0]))
        {
            return $matches[0];
        }
        return NULL;
    }
}