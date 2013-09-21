<?php

class ConnectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        Framework\Database\Exception\Implementation
     * @expectedExceptionMessage ops method not implemented
     */
    public function testExceptionNotImplementMethod()
    {
        $database = new Framework\Database();
        assertEquals($database->ops(), 'willThrowException');
    }
    
    public function testIsReturnSelf()
    {
        $database = new Framework\Database(array(
            'type' => 'mysql'
        ));
        
        assertInstanceOf('Framework\Database\Connector', $database->initialize());
    }
}