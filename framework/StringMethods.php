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
    
    public static function split($string, $pattern, $limit = NULL)
    {
        $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
        return preg_split(self::normalize($pattern), $string, $limit, $flags);
    }
}