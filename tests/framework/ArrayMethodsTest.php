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
}