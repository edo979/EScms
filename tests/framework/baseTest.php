<?php

class BaseTest extends PHPUnit_Framework_TestCase  {
    
    public function test_is_working()
    {
        new Framework\Base;
        
        assertCount(2, ['foo', 'bar']);
    }
}