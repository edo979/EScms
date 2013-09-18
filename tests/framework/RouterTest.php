<?php

class RouterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException        Framework\Router\Exception\Implementation
     * @expectedExceptionMessage ops method not implemented
     */
    public function testExceptionForNotImplementedMethod()
    {
        $router = new Framework\Router();

        $router->ops();
        assertEquals($router, 'throwException');
    }

    public function testSetGetRemoveRouteFromRouter()
    {
        $router = new Framework\Router();
        $simpleRoute = new Framework\Router\Route\Simple(array(
            'pattern'    => ':name/profile',
            'controller' => 'index',
            'action'     => 'home'
        ));

        $router->addRoute($simpleRoute);
        $list = $router->getRoutes();

        assertEquals($list, array(':name/profile' => 'Framework\Router\Route\Simple'));

        // Remove route
        $router->removeRoute($simpleRoute);
        $list = $router->getRoutes();

        assertEquals($list, array());
    }

}