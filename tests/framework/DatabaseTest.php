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
        $db = new Framework\Database(array(
            'type' => 'mysql'
        ));
        $connector = $db->initialize();

        assertInstanceOf('Framework\Database\Connector\Mysql', $connector);

        // Set database settings using configuration and registry
        $instance = new Framework\Configuration(array(
            'type' => 'array'
        ));
        Framework\Registry::set('configuration', $instance);

        // Get Type from Configuration using registry
        $dbConf = new Framework\Database();
        // Get Connector
        $connectorConf = $dbConf->initialize();
        assertInstanceOf('Framework\Database\Connector', $connectorConf);
        
    }

}