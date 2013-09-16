<?php

class ArrTest extends PHPUnit_Framework_TestCase
{

    protected $driver;

    public function setUp()
    {
        $this->driver = new Framework\Configuration\Driver\Arr;
    }

    /**
     * @expectedException        Framework\Configuration\Exception\Argument
     * @expectedExceptionMessage $path argument is not valid
     */
    public function testValidPathToSettingsArray()
    {
        $this->driver = new Framework\Configuration\Driver\Arr;
        $parse = $this->driver->parse('');
    }

    public function testIncludingSettingsData()
    {
        $settings = array();
        
        $this->driver = new Framework\Configuration\Driver\Arr;
        $settings = $this->driver->parse('application/configuration/database');
        
        assertGreaterThan(1, sizeof($settings));
    }

}