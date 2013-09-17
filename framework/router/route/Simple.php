<?php

namespace Framework\Router\Route;

use Framework\Router as Router;
use Framework\ArrayMethods as ArrayMethods;

/**
 * Matching simple route (user/:id)
 */
class Simple extends Router\Route
{

    public function match($url)
    {
        $pattern = $this->pattern;

        //get keys
        preg_match_all('#:([a-zA-z0-9]+)#', $pattern, $keys);

        if (sizeof($keys) && sizeof($keys[0]) && sizeof($keys[1]))
        {
            $keys = $keys[1];
        }
        else
        {
            return preg_match("#^{$pattern}$#", $url);
        }

        //normalize pattern
        $pattern = preg_replace('#(:[a-zA-z0-9]+)#', '([a-zA-z0-9-_]+)', $pattern);

        preg_match_all("#^{$pattern}$#", $url, $values);

        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1]))
        {
            // unset matched url
            unset($values[0]);

            // values found, modify parameters and return

            $derived = array_combine($keys, ArrayMethods::flatten($values));
            $this->parameters = array_merge($this->parameters, $derived);

            return TRUE;
        }

        return FALSE;
    }

}