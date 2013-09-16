<?php

class DriverTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException        Framework\Configuration\Exception\Implementation
     * @expectedExceptionMessage ops method not implemented
     */
    public function testExceptionForNotImplementMethod()
    {
        $driver = new Framework\Configuration\Driver\Arr();
        assertEquals($driver->ops(), 'willThrowException');
    }

    public function testForGetRightDriver()
    {
        $driver = new Framework\Configuration\Driver\Arr();
        $result = $driver->initialize();

        assertInstanceOf('Framework\Configuration\Driver\Arr', $result);
    }

}