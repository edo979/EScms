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
        $isEmpty    = empty($this->_service);
        $isInstance = $this->_service instanceof \MySqli;

        if ($this->isConnected && $isInstance && !$isEmpty)
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
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

    /**
     * 
     * @return \Framework\Database\Connector\Mysql (this)
     */
    public function disconnect()
    {
        if ($this->_isValidService())
        {
            $this->isConnected = FALSE;
            $this->_service->close();
        }

        return $this;
    }

    /**
     * Build class for basic CRUD on database
     * 
     * @return \Framework\Database\Query\Mysql
     */
    public function query()
    {
        return new Database\Query\Mysql(array(
            'connector' => $this
        ));
    }

    /**
     * Execute raw query
     * 
     * @param string $sql
     * @return mixed
     * @throws Exception\Service
     */
    public function execute($sql)
    {
        if (!$this->_isValidService())
        {
            throw new Exception\Service("Not connected to a valid service");
        }

        return $this->_service->query($sql);
    }

    /**
     *
     * @param string $value
     * @return string
     * @throws Exception\Service
     */
    public function escape($value)
    {
        if (!$this->_isValidService())
        {
            throw new Exception\Service("Not connected to a valid service");
        }

        return $this->_service->real_escape_string($value);
    }

    public function getLastInsertId()
    {
        if (!$this->_isValidService())
        {
            throw new Exception\Service("Not connected to a valid service");
        }

        return $this->_service->insert_id;
    }

    public function getAffectedRows()
    {
        if (!$this->_isValidService())
        {
            throw new Exception\Service("Not connected to a valid service");
        }

        return $this->_service->affected_rows;
    }

    public function getLastError()
    {
        if (!$this->_isValidService())
        {
            throw new Exception\Service("Not connected to a valid service");
        }

        return $this->_service->error;
    }

}