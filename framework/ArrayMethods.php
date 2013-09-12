<?php

namespace Framework;

class ArrayMethods
{

    public static function trim($array)
    {
        return array_map(function ($item) {
              return trim($item);
          }, $array);
    }

    public static function clean($array)
    {
        return array_filter($array, function($item) {
              return !empty($item);
          });
    }

}