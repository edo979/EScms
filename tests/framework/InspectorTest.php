<?php

namespace Framework;

// Class for testing using Inspector
class TestClass
{

    /**
     * @read
     */
    private $testProperty;

}

function inspectorFactory($class = null)
{
    return new Inspector(new TestClass());
}

class InspectorTest extends \PHPUnit_Framework_TestCase
{

    // Using reflection to test protected and private method
    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(inspectorFactory());
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testInspectorConstructGetClassForRead()
    {
        $class = (inspectorFactory());
        // Testing protected Inspector properties: $_class
        assertEquals(\PHPUnit_Framework_Assert::readAttribute($class, '_class'), new TestClass()
        );
    }

    function testParseMethodForReadingComment()
    {
        $parse = self::getMethod('_parse');
        $inspector = inspectorFactory();
        $comment = $parse->invokeArgs($inspector, array('@comment'));

        assertEquals($comment, array('@comment'));
    }

}