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
        
        $meta = $matches;
        
        return $meta;
    }

}