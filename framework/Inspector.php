<?php

namespace Framework;

class Inspector
{

    protected $_class;
    protected $_meta = array(
        'class' => array()
    );

    public function __construct($class)
    {
        $this->_class = $class;
    }

    protected function _getClassComment()
    {
        $reflection = new \ReflectionClass($this->_class);
        return $reflection->getDocComment();
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
        }

        return $meta;
    }

    public function getClassMeta()
    {
        if (!isset($_meta['class']))
        {
            $comment = $this->_getClassComment();

            if (!empty($comment))
            {
                $_meta['class'] = $this->_parse($comment);
            }
            else
            {
                $_meta['class'] = NULL;
            }
        }

        return $_meta['class'];
    }

}