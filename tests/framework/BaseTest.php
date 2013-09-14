<?php

namespace Framework;

use Framework\Base as Base;
use Framework\Inspector as Inspector;

//Test class
class BaseTestClass extends Base
{

    public $classProperty;
    
    /**
     *@readwrite
     */
    protected $_classPropertyNew;

    public function setClassProperty($param)
    {
        $this->classProperty = $param;
    }

}

class BaseTest extends \PHPUnit_Framework_TestCase
{

    public function testSetingPropertiesWhenConstructClass()
    {
        $class = new BaseTestClass(array('classProperty' => 'someValue'));
        assertEquals($class->classProperty, 'someValue');
    }

    public function testForInstanceInspector()
    {
        $classInstaceOfInspector = \PHPUnit_Framework_Assert::readAttribute(
            new BaseTestClass, '_inspector');
        assertEquals($classInstaceOfInspector instanceof Inspector, TRUE);
    }
   
    public function testGeterSeterMethodsInBaseClass()
    {
        $class = new BaseTestClass(array('classPropertyNew' => 'someValue'));
        
        assertEquals(\PHPUnit_Framework_Assert::readAttribute(
            $class, '_classPropertyNew'), 'someValue');
        
        // Get property whitouth Getter using DocComment @readwrite
        assertEquals($class->classPropertyNew, 'someValue');
        
        // Set property
        $class->classPropertyNew = 'newValue';
        assertEquals($class->classPropertyNew, 'newValue');
    }

}
