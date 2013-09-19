<?php

namespace Framework;

use Framework\Base as Base;
use Framework\Registry as Registry;
use Framework\Inspector as Inspector;
use Framework\Router\Exception as Exception;

/**
 * Matching rute using route subclas, call hooks and dispatch controller
 */
class Router extends Base
{

    /**
     * @readwrite
     */
    protected $_url;

    /**
     * @readwrite
     */
    protected $_extension;

    /**
     * @read
     */
    protected $_controller;

    /**
     * @read
     */
    protected $_action;
    protected $_routes = array();
    private $_inspector;

    /**
     * executed Controller method,
     * if method must execute only once
     * hook check is method execute and run method if is not in array
     * 
     * @var type string
     */
    private $_run = array();

    public function _getExceptionForImplementation($method)
    {
        return new Exception\Implementation("{$method} method not implemented");
    }

    public function addRoute($route)
    {
        $this->_routes[] = $route;
        return $this;
    }

    public function getRoutes()
    {
        $list = array();

        foreach ($this->_routes as $route)
        {
            $list[$route->pattern] = get_class($route);
        }

        return $list;
    }

    public function removeRoute($route)
    {
        foreach ($this->_routes as $i => $stored)
        {
            if ($stored == $route)
            {
                unset($this->_routes[$i]);
            }
        }

        return $this;
    }

    public function dispatch()
    {
        $url = $this->url;
        $parameters = array();
        $controller = 'index';
        $action = 'index';

        foreach ($this->_routes as $route)
        {
            $matches = $route->match($url);
            if ($matches)
            {
                $controller = $route->controller;
                $action = $route->action;
                $parameters = $route->parameters;

                $this->_pass($controller, $action, $parameters);
                return;
            }
        }

        $parts = explode('/', trim($url, '/'));

        if (sizeof($parts) > 0)
        {
            $controller = $parts[0];

            if (sizeof($parts) >= 2)
            {
                $action = $parts[1];
                $parameters = array_slice($parts, 2);
            }
        }

        $this->_pass($controller, $action, $parameters);
    }

    protected function _pass($controller, $action, $parameters)
    {
        $name = ucfirst($controller);

        $this->_controller = $controller;
        $this->_action = $action;

        try
        {
            $instance = new $name(array(
                'parameters' => $parameters
            ));
            Registry::set('controller', $instance);
        }
        catch (\Exception $e)
        {
            throw new Exception\Controller("Controller {$name} not found");
        }

        if (!method_exists($instance, $action))
        {
            $instance->willRenderLayoutView = false;
            $instance->willRenderActionView = false;

            throw new Exception\Action("Action {$action} not found");
        }

        $this->_inspector = new Inspector($instance);
        $methodMeta = $this->_inspector->getMethodMeta($action);

        // TODO: method meta @private @protected

        $this->_hook($methodMeta, '@before');

        call_user_func_array(array(
            $instance,
            $action
        ), is_array($parameters) ? $parameters : array());

        $this->_hook($methodMeta, '@after');

        // unset Controller
        Registry::erase('controller');
    }

    private function _hook($meta, $type)
    {
        if (isset($meta[$type]))
        {
            foreach ($meta[$type] as $method)
            {
                $hookMeta = $this->_inspector->getMethodMeta($method);

                if (in_array($method, $this->_run) && !empty($hookMeta['@once']))
                {
                    continue;
                }

                $controller = Registry::get('controller');
                $controller->{$method}();
                $this->_run[] = $method;
            }
        }
    }

}