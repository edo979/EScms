<?php

namespace Framework;

class Inspector
{

    protected $_class;

    public function __construct($class)
    {
        $this->_class = $class;
    }

    protected function _parse($comment)
    {
        $meta = array();
        $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)";
        $matches = StringMethods::match($comment, $pattern);

        if ($matches != NULL)
        {
            foreach ($matches as $match)
            {
                $parts = ArrayMethods::clean(
                    ArrayMethods::trim(
                      StringMethods::split($match, "[\s]", 2)
                    )
                );
            }

            $meta[$parts[0]] = TRUE;

            if (sizeof($parts) > 1)
            {
                $meta[$parts[0]] = ArrayMethods::clean(
                    ArrayMethods::trim(
                      StringMethods::split($parts[1], ",")
                    )
                );
            }
        }

        return $meta;
    }

}