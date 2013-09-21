<?php

class DatabaseTest extends PHPUnit_Framework_TestCase
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

    /**
     * @expectedException        Framework\Database\Exception\Argument
     * @expectedExceptionMessage Invalid type
     */
    public function testWrongDatabaseType()
    {
        $database = new Framework\Database();
        $database->initialize();

        assertEquals($database, 'throwArgumentException');
    }

    public function testDatabaseFactoryMakeConnector()
    {
        $database = new Framework\Database(array(
            'type' => 'mysql'
        ));
        $connector = $database->initialize();

        assertInstanceOf('Framework\Database\Connector\Mysql', $connector);
    }

}