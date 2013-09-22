<?php

/**
 * Extends class for get, set protected method, property
 */
class Database extends Framework\Database\Connector\Mysql
{

    protected $_host = '127.0.0.1';
    protected $_username = 'error';
    protected $_password = 'error';

    function get_isConnected()
    {
        return $this->_isConnected;
    }

    function get_service()
    {
        return $this->_service;
    }

}

class MysqlTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException        Framework\Database\Exception\Service
     * @expectedExceptionMessage Unable to connect to service
     */
    public function testExceptionForConnectionError()
    {
        $mysql = new Database();
        $mysql->connect(); // Throw Exception
    }

}