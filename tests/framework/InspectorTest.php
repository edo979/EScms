<?php

namespace Framework;

// Class for testing using Inspector
/**
 * @read
 * @author Edis, Selimovic
 */
class TestClass
{

    /**
     * @read
     */
    private $testProperty;

}

class TestClass1
{
    private $testProperty;
}

function inspectorFactory($class = null)
{
    return new Inspector($class);
}

class InspectorTest extends \PHPUnit_Framework_TestCase
{

    // Using reflection to test protected and private method
    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(inspectorFactory(new TestClass));
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testInspectorConstructGetClassForRead()
    {
        $class = (inspectorFactory(new TestClass));
        // Testing protected Inspector properties: $_class
        assertEquals(\PHPUnit_Framework_Assert::readAttribute($class, '_class'), new TestClass()
        );
    }

    public function testParseMethodForMakingKeyValuePairs()
    {
        // @read, @write, @readwrite ...
        $parse = self::getMethod('_parse');
        $inspector = inspectorFactory(new TestClass);

        $comment = $parse->invokeArgs($inspector, array('@read'));
        assertEquals($comment, array('@read' => TRUE));

        $comment = $parse->invokeArgs($inspector, array('@before method1, method2'));
        assertEquals($comment, array('@before' => array('method1', 'method2')));
    }

    public function testGetClassMeta()
    {
        // Using testing class
        $inspector = inspectorFactory(new TestClass);
        $result = $inspector->getClassMeta();
        assertEquals($result, array(
            '@read' => TRUE,
            '@author' => array('Edis', 'Selimovic')
        ));
        
        // Testing class whithout comments
        $inspector = inspectorFactory(new TestClass1);
        $result = $inspector->getClassMeta();
        assertEquals($result, NULL);
    }

}