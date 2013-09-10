<?php

namespace Framework;

class Inspector
{

    protected $_class;

    public function __construct($class)
    {
        $this->_class = $class;
    }

}