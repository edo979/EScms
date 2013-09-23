<?php

class QueryTest extends PHPUnit_Framework_TestCase
{

    public $mock;
    public $query;
    public $reflector;

    public function setUp()
    {
        // Mock connector
        $this->mock = $this->getMock('Framework\Database\Connector\Mysql');
        $this->mock->expects(once())
          ->method('escape')
          ->with($this->stringContains('test'));

        // Make instance of testing class
        $this->query = new Framework\Database\Query;

        // Create reflection,
        // using protected method and property in tested class
        $this->reflector = new ReflectionClass($this->query);

        $_connector = $this->reflector->getProperty('_connector');
        $_connector->setAccessible(TRUE);
        $_connector->setValue($this->query, $this->mock);
    }

    public function tearDown()
    {
        $this->mock = null;
        $this->query = null;
        $this->reflector = null;
    }

    public function testQuotingInputDataForMysqlDatabase()
    {
        // test method for proper call
        $_quote = $this->reflector->getMethod('_quote');
        $_quote->setAccessible(TRUE);

        //test call mocked object
        $_quote->invoke($this->query, 'test');
    }

}