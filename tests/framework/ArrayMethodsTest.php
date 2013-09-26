<?php

namespace Framework;

class ArrayMethodsTest extends \PHPUnit_Framework_TestCase
{

    public function testTrimArrayItems()
    {
        $result = ArrayMethods::trim(array(' spacebefore', 'spaceafter ', ' both '));
        assertEquals($result, array('spacebefore', 'spaceafter', 'both'));
    }

    public function testCleanEmptyArrayItems()
    {
        $result = ArrayMethods::clean(array('', '', 'item'));
        assertEquals(1, sizeof($result));
    }

    public function testFlattenMultidimensionalArray()
    {
        $array = array(array(1, array(2, 3)), 4);
        $result = ArrayMethods::flatten($array);

        assertEquals($result, array(1, 2, 3, 4));
    }
    
    public function testReturningFirstArrayItem()
    {
        $array = array('user' => 'john', 'id' => 1);
        $result = ArrayMethods::first($array);
        
        assertEquals('john', $result);
    }

}