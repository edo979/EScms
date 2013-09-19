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
    public $once = 0;
    public $hook = 0;

    /**
     * @before TestOnce, TestHook
     * @after TestOnce, TestHook
     */
    public function TestMethod()
    {
        
    }

    /**
     * @once
     */
    public function TestOnce()
    {
        $this->once = $this->once + 1;
    }

    public function TestHook()
    {
        $this->hook = $this->hook + 1;
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
        $router = new Framework\Router(array(
            "url" => 'ControllerTest/FalseTestMethod/parameter'
        ));

        // Make Instance of Controller
        $router->dispatch();

        $controller = Framework\Registry::get('controller');
        assertInstanceOf('ControllerTest', $controller);

        assertEquals($controller->willRenderActionView, FALSE);
    }

    public function testHookForCallingMethodsBeforeAfter()
    {
        $router = new Framework\Router(array(
            "url" => 'ControllerTest/TestMethod/parameter'
        ));

        // Make Instance of Controller
        $router->dispatch();

        $controller = Framework\Registry::get('controller');

        // Controller should be call methods in metaData
        assertEquals($controller->once, 1);
        assertEquals($controller->hook, 2);
    }

}