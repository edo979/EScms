<?php

class QueryTest extends PHPUnit_Framework_TestCase
{

    public $mock;
    public $query;
    public $reflector;

    public function setUp()
    {
        $this->mock = $this->getMock('Framework\Database\Connector\Mysql');

        // Make instance of testing class
        $this->query = new Framework\Database\Query;

        // Create reflection,
        // using protected method and property in tested class
        $this->reflector = new ReflectionClass($this->query);

        // Set connector (depedency)
        $_connector = $this->reflector->getProperty('_connector');
        $_connector->setAccessible(TRUE);
        $_connector->setValue($this->query, $this->mock);
    }

    public function tearDown()
    {
        $this->_mock = null;
        $this->query = null;
        $this->reflector = null;
    }

    public function testQuotingInputDataForMysqlDatabase()
    {
        // Mock method from connector
        $this->mock->expects(exactly(3))
          ->method('escape')
          ->with($this->stringContains('test'));

        $_quote = $this->reflector->getMethod('_quote');
        $_quote->setAccessible(TRUE);

        // call mocked object vith string
        $_quote->invoke($this->query, 'test');

        // call mocked object with array
        $_quote->invoke($this->query, array('test', 'test'));
        
        // call mocked object with null
        $_quote->invoke($this->query, NULL);
        
        // call mocked object with boolean
        $_quote->invoke($this->query, FALSE);

    }

}