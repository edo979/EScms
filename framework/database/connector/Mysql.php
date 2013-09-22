<?php

namespace Framework\Database\Connector;

use Framework\Database as Database;
use Framework\Database\Exception as Exception;

/**
 * Connector to Mysql
 *
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

    /**
     *
     * @var object (mysqli)
     */
    protected $_service;

    /**
     * Validate connection to database
     * 
     * @return boolean
     */
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

    /**
     * Connect to database using MySQLi
     * 
     * @return \Framework\Database\Connector\Mysql (this)
     */
    public function connect()
    {
        if (!$this->_isValidService())
        {
            try
            {
                $this->_service = mysqli_connect(
                $this->_host, $this->_username, $this->_password, $this->_schema, $this->_port
                );
            }
            catch (\Exception $e)
            {
                throw new Exception\Service("Unable to connect to service");
            }

            $this->isConnected = TRUE;
        }

        return $this;
    }

}