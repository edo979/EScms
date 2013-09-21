<?php

namespace Framework\Database\Connector;

use Framework\Database as Database;
use Framework\Database\Exception as Exception;

/**
 * Description of Mysql
 *
 * @author Comp
 */
class Mysql extends Database\Connector
{

    /**
     * @readwrite
     */
    protected $_host;

    /**
     * @readwrite
     */
    protected $_username;

    /**
     * @readwrite
     */
    protected $_password;

    /**
     * @readwrite
     */
    protected $_schema;

    /**
     * @readwrite
     */
    protected $_port = "3306";

    /**
     * @readwrite
     */
    protected $_charset = "utf8";

    /**
     * @readwrite
     */
    protected $_engine = "InnoDB";

    /**
     * @readwrite
     */
    protected $_isConnected = false;
    protected $_service;

    protected function _isValidService()
    {
        $isEmpty = empty($this->_service);
        $isInstance = $this->_service instanceof \MySqli;
        
        if ($this->isConnected && $isInstance && !isEmpty)
        {
            return TRUE;
        }

        return FALSE;
    }

    public function connect()
    {
        if (!$this->_isValidService())
        {
            $this->isConnected = TRUE;
        }
        
        return $this;
    }

}