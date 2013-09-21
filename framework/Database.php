<?php

namespace Framework;

use Framework\Base as Base;
use Framework\Database as Database;
use Framework\Registry as Rgistry;
use Framework\Database\Exception as Exception;

/**
 * Faktory for make database connector, from configuration settings. 
 *
 * @return object
 */
class Database extends Base
{

    /**
     * @readwrite
     */
    protected $_type;

    /**
     * @readwrite
     */
    protected $_options;

    protected function _getExceptionForImplementation($method)
    {
        return new Exception\Implementation("{$method} method not implemented");
    }

    public function initialize()
    {
//        if(!$this->type)
//        {
//            
//        }

        if (!$this->type)
        {
            throw new Exception\Argument('Invalid type');
        }

        switch ($this->type)
        {
            case 'mysql':

                return new Database\Connector\Mysql($this->options);
                break;

            default:
                throw new Exception\Argument('Invalid type');
                break;
        }
    }

}