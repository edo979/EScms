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

    function testParseMethodForMakingKeyValuePairs()
    {
        // @read, @write, @readwrite ...
        $parse = self::getMethod('_parse');
        $inspector = inspectorFactory();
        
        $comment = $parse->invokeArgs($inspector, array('@read'));
        assertEquals($comment, array('@read' => TRUE));
        
        $comment = $parse->invokeArgs($inspector, array('@before method1, method2'));
        assertEquals($comment, array('@before' => array('method1', 'method2')));
    }

}