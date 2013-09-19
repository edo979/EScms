<?php

class ForTestDispachMethod extends Framework\Router
{

    /**
     * @readwrite
     */
    protected $_testCallPassMethod = array();

    protected function _pass($controller, $action, $parameters)
    {
        $this->testCallPassMethod = array($controller, $action, $parameters);
    }

}

class ControllerTest extends Framework\Base
{

    /**
     * @readwrite
     */
    protected $_parameters = array();

    /**
     * @readwrite
     */
    protected $_willRenderLayoutView = true;

    /**
     * @readwrite
     */
    protected $_willRenderActionView = true;

    public function TestMethod()
    {
        
    }

}

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

    public function testPassingDataToMethodWhichWillCallController()
    {
        //test defined route
        $router = new ForTestDispachMethod(array(
            "url" => 'john/profile'
        ));
        $simpleRoute = new Framework\Router\Route\Simple(array(
            'pattern'    => ':name/profile',
            'controller' => 'index',
            'action'     => 'home'
        ));

        $router->addRoute($simpleRoute);
        $router->dispatch();

        assertEquals($router->testCallPassMethod, array('index', 'home', array(
                'name' => 'john')
        ));

        // test inferred route
        $router = new ForTestDispachMethod(array(
            "url" => 'user/search/parameter'
        ));
        $router->dispatch();

        assertEquals($router->testCallPassMethod, array('user', 'search', array(
                'parameter')
        ));
    }

    /**
     * @expectedException        Framework\Router\Exception\Action
     * @expectedExceptionMessage Action FalseTestMethod not found
     */
    public function testPassingDataToController()
    {
        // Make Instance of Controller
        $router = new Framework\Router(array(
            "url" => 'ControllerTest/FalseTestMethod/parameter'
        ));
        $router->dispatch();
        $controller = Framework\Registry::get('controller');
        assertInstanceOf('ControllerTest', $controller);

        assertEquals($controller->willRenderActionView, FALSE);
    }

}