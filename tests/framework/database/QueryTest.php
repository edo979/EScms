<?php

class QueryTest extends PHPUnit_Framework_TestCase
{

    public function testQuotingInputDataForMysqlDatabase()
    {
        // Mock connector
        $mock = $this->getMock('Framework\Database\Connector\Mysql');
        $mock->expects(once())
          ->method('escape')
          ->with($this->stringContains('test'));

        // Make instance of testing class
        $mysql = new Framework\Database\Query;

        // Create reflection,
        // using protected method and property in tested class
        $reflector = new ReflectionClass($mysql);

        $_connector = $reflector->getProperty('_connector');
        $_connector->setAccessible(TRUE);
        $_connector->setValue($mysql, $mock);

        $_quote = $reflector->getMethod('_quote');
        $_quote->setAccessible(TRUE);

        //test call mocked object
        $result = $_quote->invoke($mysql, 'test');
    }

}