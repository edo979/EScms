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

    protected function _buildSelect()
    {
        $fields = array();
        $where = $order = $join = $limit = '';
        $template = "SELECT %s FROM %s %s %s %s %s";
        
        foreach ($this->fields as $table => $_fields)
        {
            foreach ($_fields as $field => $alias)
            {
                if (is_string($field))
                {
                    $fields[] = "{$field} AS {$alias}";
                }
                else
                {
                    $fields[] = "{$alias}";
                }
            }
        }
        
        $fields = join(", ", $fields);
        
        $_join = $this->join;
        if (!empty($_join))
        {
            $join = join(" ", $_join);
        }
        
        $_where = $this->where;
        if (!empty($_where))
        {
            $joined = join(" AND ", $_where);
            $where = "WHERE {$joined}";
        }
        
        $_order = $this->order;
        if (!empty($_order))
        {
            $order = "ORDER BY {$_order} {$this->direction}";
        }
        
        $_limit = $this->limit;
        if (!empty($_limit))
        {
            $_offset = $this->offset;
            if (!empty($_offset))
            {
                $limit = "LIMIT {$_limit}, {$_offset}";
            }
            else
            {
                $limit = "LIMIT {$limit}";
            }
        }
        
        return sprintf($template, $fields, $this->from, $join, $where, $order, $limit);
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

        $this->_fields += array($join => $fields);
        $this->_join[] = "JOIN {$join} ON {$on}";

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

    public function where()
    {
        $arguments = func_get_args();
        if (sizeof($arguments) < 1)
        {
            throw new Exception\Argument('Invalid argument');
        }

        $arguments[0] = preg_replace('#\?#', '%s', $arguments[0]);
        $parameters = array_slice($arguments, 1, NULL, TRUE);

        foreach ($parameters as $i => $parameter)
        {
            $arguments[$i] = $this->_quote($arguments[$i]);
        }

        $this->_where[] = call_user_func_array('sprintf', $arguments);
    }

}