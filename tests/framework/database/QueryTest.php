<?php

class QueryTest extends PHPUnit_Framework_TestCase
{
    public function testQuotingInputDataForMysqlDatabase()
    {
        // using reflection for protected method
        $_quote = new ReflectionMethod('Framework\Database\Query', '_quote');
        $_quote->setAccessible(TRUE);
        
        //test string qouting
        $result = $_quote->invoke(new Framework\Database\Query, "test'error'");
        
        assertEquals($result, 'test\'error\'');
    }
}