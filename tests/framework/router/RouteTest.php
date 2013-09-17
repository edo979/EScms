<?php

class RouteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        Framework\Router\Exception\Implementation
     * @expectedExceptionMessage ops method not implemented
     */
    public function testForNotImplementedMethods()
    {
        $route = new Framework\Router\Route;
        assertEquals($route->ops(), 'willThrowException');
    }
}