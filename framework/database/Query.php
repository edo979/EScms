<?php

namespace Framework\Database;

use Framework\Base as Base;
use Framework\ArrayMethods as ArrayMethods;
use Framework\Database\Exception as Exception;

class Query extends Base
{

    /**
     * @readwrite
     */
    protected $_connector;

    /**
     * @read
     */
    protected $_from;

    /**
     * @read
     */
    protected $_fields;

    /**
     * @read
     */
    protected $_limit;

    /**
     * @read
     */
    protected $_offset;

    /**
     * @read
     */
    protected $_order;

    /**
     * @read
     */
    protected $_direction;

    /**
     * @read
     */
    protected $_join = array();

    /**
     * @read
     */
    protected $_where = array();

    protected function _getExceptionForImplementation($method)
    {
        return new Exception\Implementation("{$method} method not implemented");
    }
    
    protected function _quote($value)
    {
        if (is_string($value))
        {
            $escaped = $this->connector->escape($value);
            return "'{$escaped}'";
        }
        
        if (is_array($value))
        {
            $buffer = array();
            
            foreach ($value as $i)
            {
                array_push($buffer, $this->_quote($i));
            }
            
            $buffer =  join(", ", $buffer);
            return $buffer;
        }
        
        if (is_null($value))
        {
            return "NULL";
        }
        
        if (is_bool($value))
        {
            return (int) $value;
        }
        
        return $this->connector->escape($value);
    }

}