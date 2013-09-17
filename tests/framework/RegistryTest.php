<?php

class TestClass
{

    public function initialize()
    {
        return 'instance';
    }

}

class RegistryTest extends PHPUnit_Framework_TestCase
{

    public function testSetGetInstaceInRegistry()
    {
        $testClass = new TestClass;

        Framework\Registry::set('testClass', $testClass->initialize());
        $instance = Framework\Registry::get('testClass');

        assertEquals($instance, 'instance');

        // unset item in registry array
        Framework\Registry::erase('testClass');
        $instance = Framework\Registry::get('testClass');

        assertEquals($instance, NULL);
    }

}