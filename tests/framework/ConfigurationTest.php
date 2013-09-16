<?php

namespace Framework;

//use Framework\Base as Base;
//use Framework\Inspector as Inspector;
use Framework\Configuration\Driver as Driver;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    protected $configuration;

    protected function setUp()
    {
        $this->configuration = new Configuration(array('type' => 'array'));
    }

    protected function tearDown()
    {
        $this->configuration = NULL;
    }

    /**
     * @expectedException        Framework\Configuration\Exception\Implementation
     * @expectedExceptionMessage ops method not implemented
     */
    public function testExceptionNotImplementMethod()
    {
        assertEquals($this->configuration->ops(), 'willThrowException');
    }

    public function testSettingPropertyUsingInspectorWhenClassInitialize()
    {
        assertEquals($this->configuration->type, 'array');
    }

    /**
     * @expectedException        Framework\Configuration\Exception\Argument
     * @expectedExceptionMessage Invalid type
     */
    public function testCreatingConfigurationDriverFactory()
    {
        $driver = $this->configuration->initialize();
        assertEquals($driver, new Driver\Arr);

        $configuration = new Configuration();
        $configuration->initialize();
        assertEquals($configuration->type, 'willThrowException');
    }

}