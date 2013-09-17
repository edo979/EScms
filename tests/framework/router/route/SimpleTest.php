<?php

class SimpleTest extends PHPUnit_Framework_TestCase
{

    public function testUrlMatchingWithKeysInPattern()
    {
        $simpleRoute = new Framework\Router\Route\Simple(array(
            'pattern'    => ':name/profile',
            'controller' => 'index',
            'action'     => 'home'
        ));

        $match = $simpleRoute->match('john/profile');
        assertEquals($match, TRUE);

        assertEquals($simpleRoute->parameters, array('name' => 'john'));

        $match = $simpleRoute->match('john/edit');
        assertEquals($match, FALSE);
    }

    public function testUrlMatchingWithPattern()
    {
        $simpleRoute = new Framework\Router\Route\Simple(array(
            'pattern'    => 'profile/edit',
            'controller' => 'index',
            'action'     => 'home'
        ));

        $match = $simpleRoute->match('profile/edit');
        assertEquals($match, TRUE);

        $match = $simpleRoute->match('profile/delete');
        assertEquals($match, FALSE);
    }

}