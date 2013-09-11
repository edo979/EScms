<?php

class StringMethodsTest extends PHPUnit_Framework_TestCase
{
    public function testForPatternMatchingString()
    {
        // full match if any $match[0]
        $pattern = "[a-z]";
        $result = Framework\StringMethods::match('test', $pattern);
        assertEquals($result, array('t','e','s','t'));
        
        // match substring $match[1]
        $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)";
        $result = Framework\StringMethods::match('@testVariable value1, value2', $pattern);
        assertEquals($result, array('@testVariable value1, value2'));
        
        // no match
        $pattern = "[0-9]";
        $result = Framework\StringMethods::match('test', $pattern);
        assertEquals($result, NULL);
    }
}
