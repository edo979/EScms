<?php

/**
 * Extends class for get, set protected method, property
 */
class Database extends Framework\Database\Connector\Mysql
{

    protected $_service = NULL;

    function get_isConnected()
    {
        return $this->_isConnected;
    }

}

class MysqlTest extends PHPUnit_Framework_TestCase
{

    public function testWeHaveValidService()
    {
        $service = new stdClass();
        $mysql = new Database();

        // For first time connection
        assertNotInstanceOf('Framework\Database\Connector\Mysql', $service);
        $service = $mysql->connect();
        assertEquals($mysql->_isConnected, TRUE);

        // Test for connected service
        assertInstanceOf('Framework\Database\Connector\Mysql', $service);
    }

}