<?php
namespace Framework;

class StringMethodsTest extends \PHPUnit_Framework_TestCase
{
    public function testForPatternMatchingString()
    {
        // full match if any $match[0]
        $pattern = "[a-z]";
        $result = StringMethods::match('test', $pattern);
        assertEquals($result, array('t','e','s','t'));
        
        // match substring $match[1]
        $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)";
        $result = StringMethods::match('@testVariable value1, value2', $pattern);
        assertEquals($result, array('@testVariable value1, value2'));
        
        // no match
        $pattern = "[0-9]";
        $result = StringMethods::match('test', $pattern);
        assertEquals($result, NULL);
    }
    
    public function testSplitingStringMethod()
    {
        $result = StringMethods::split('@before method1, method2', "[\s]", 2);
        assertEquals($result, array('@before', 'method1, method2'));
        
        $result = StringMethods::split('method1,method2,', ",");
        assertEquals($result, array('method1', 'method2'));
    }
}
