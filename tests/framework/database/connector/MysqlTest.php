<?php

/**
 * Extends class for get, set protected method, property
 */
class Database extends Framework\Database\Connector\Mysql
{

    protected $_host     = '127.0.0.1';
    protected $_username = 'error';
    protected $_password = 'error';

    function get_service()
    {
        return $this->_service;
    }

    function set_service($mock)
    {
        $this->_service = $mock;
    }

}

class MysqlTest extends PHPUnit_Framework_TestCase
{

    public $mysqli;

    public function setUp()
    {
        $this->mysqli = new Database();
    }

    public function tearDown()
    {
        $this->mysqli = NULL;
    }

    /**
     * @expectedException        Framework\Database\Exception\Service
     * @expectedExceptionMessage Unable to connect to service
     */
    public function testExceptionForConnectionError()
    {
        $this->mysqli->connect(); // Throw Exception
    }

    public function testDisconnectFromDatabase()
    {
        $mock = $this->getMock('mysqli');
        $mock->expects($this->once())
          ->method('close');

        $this->mysqli->_service    = $mock;
        $this->mysqli->isConnected = true;

        $this->mysqli->disconnect();
    }

    public function testGettingMysqlClassForExecutingQuery()
    {
        $query = $this->mysqli->query();
        assertInstanceOf('Framework\Database\Query\Mysql', $query);
    }

    public function testExecuteRawQuery()
    {
        $mock = $this->getMock('mysqli');
        $mock->expects($this->once())
          ->method('query')
          ->with($this->anything());


        $this->mysqli->_service    = $mock;
        $this->mysqli->isConnected = true;

        $this->mysqli->execute('sql');
    }

    public function testEscapingValue()
    {
        $mock = $this->getMock('mysqli');
        $mock->expects($this->once())
          ->method('real_escape_string')
          ->with($this->anything());

        $this->mysqli->_service    = $mock;
        $this->mysqli->isConnected = true;

        $this->mysqli->escape('sql');
    }

}