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

    /**
     * @read
     */
    private $testPropertyNew;

    /**
     * @read
     */
    private function testMethod()
    {
        
    }

    /**
     * @read
     */
    private function testMethodNew()
    {
        
    }

}

class TestClass1
{

    private $testProperty;

    private function testMethod()
    {
        
    }

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
            '@read'   => TRUE,
            '@author' => array('Edis', 'Selimovic')
        ));

        // Testing class whithout comments
        $inspector = inspectorFactory(new TestClass1);
        $result = $inspector->getClassMeta();
        assertEquals($result, NULL);
    }

    public function testGetClassProperties()
    {
        $inspector = inspectorFactory(new TestClass);
        $result = $inspector->getClassProperties();
        assertEquals($result, array('testProperty', 'testPropertyNew'));
    }

    public function testGetClassMethods()
    {
        $inspector = inspectorFactory(new TestClass);
        $result = $inspector->getClassMethods();
        assertEquals($result, array('testMethod', 'testMethodNew'));
    }

    public function testGetPropertyMeta()
    {
        $inspector = inspectorFactory(new TestClass);
        $result = $inspector->getPropertyMeta('testProperty');
        assertEquals($result, array('@read' => TRUE));

        // no meta data
        $inspector = inspectorFactory(new TestClass1);
        $result = $inspector->getPropertyMeta('testProperty');
        assertEquals($result, NULL);
    }

    public function testGetMethodMeta()
    {
        $inspector = inspectorFactory(new TestClass);
        $result = $inspector->getMethodMeta('testMethod');
        assertEquals($result, array('@read' => TRUE));

        // no meta data
        $inspector = inspectorFactory(new TestClass1);
        $result = $inspector->getMethodMeta('testMethod');
        assertEquals($result, NULL);
    }

}