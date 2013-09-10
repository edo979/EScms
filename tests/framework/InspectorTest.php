<?php

namespace Framework;

// Class for testing using Inspector
class TestClass
{

    function __construct()
    {
        
    }

}

class InspectorTest extends \PHPUnit_Framework_TestCase
{

    public function testInspectorConstruct()
    {
        $class = (new Inspector(new TestClass()));
        // Testing protected Inspector properties: $_class
        assertEquals(\PHPUnit_Framework_Assert::readAttribute($class, '_class'), new TestClass()
        );
    }

}