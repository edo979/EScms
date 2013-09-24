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
    protected $_fields = array();

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

            $buffer = join(", ", $buffer);
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

    public function from($from, $fields = array("*"))
    {
        if (empty($from))
        {
            throw new Exception\Argument('Invalid argument');
        }

        $this->_from = $from;

        if ($fields)
        {
            $this->_fields[$from] = $fields;
        }

        return $this;
    }

    public function join($join, $on, $fields = array())
    {
        if (empty($join))
        {
            throw new Exception\Argument('Invalid argument');
        }

        if (empty($on))
        {
            throw new Exception\Argument('Invalid argument');
        }

        $this->_fields = $this->_fields + array($join => $fields);
        $this->_join   = "JOIN {$join} ON {$on}";

        return $this;
    }
    
    public function limit($limit, $page = 1)
    {
        if (empty($limit))
        {
            throw new Exception\Argument('Invalid argument');
        }
        
        $this->_limit = $limit;
        $this->_offset = $limit * ($page - 1);
        
        return $this;
    }
    
    public function order($order, $direction = 'asc')
    {
        if (empty($order))
        {
            throw new Exception\Argument('Invalid argument');
        }
        
        $this->_order = $order;
        $this->_direction = $direction;
        
        return $this;
    }

}