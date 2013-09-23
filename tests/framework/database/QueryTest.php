<?php

class QueryTest extends PHPUnit_Framework_TestCase
{

    public $mock;
    public $query;
    public $reflector;

    public function setUp()
    {
        $this->mock = $this->getMock('Framework\Database\Connector\Mysql');

        // Make instance of testing class
        $this->query = new Framework\Database\Query;

        // Create reflection,
        // using protected method and property in tested class
        $this->reflector = new ReflectionClass($this->query);

        // Set connector (depedency)
        $_connector = $this->reflector->getProperty('_connector');
        $_connector->setAccessible(TRUE);
        $_connector->setValue($this->query, $this->mock);
    }

    public function tearDown()
    {
        $this->_mock     = null;
        $this->query     = null;
        $this->reflector = null;
    }

    /**
     * @expectedException        Framework\Database\Exception\Implementation
     * @expectedExceptionMessage ops method not implemented
     */
    public function testExceptionNotImplementMethod()
    {
        assertEquals($this->query->ops(), 'willThrowException');
    }

    public function testQuotingInputDataForMysqlDatabase()
    {
        // Mock method from connector
        $this->mock->expects(exactly(3))
          ->method('escape')
          ->with($this->stringContains('test'));

        $_quote = $this->reflector->getMethod('_quote');
        $_quote->setAccessible(TRUE);

        // call mocked object vith string
        $_quote->invoke($this->query, 'test');

        // call mocked object with array
        $_quote->invoke($this->query, array('test', 'test'));

        // call mocked object with null
        $_quote->invoke($this->query, NULL);

        // call mocked object with boolean
        $_quote->invoke($this->query, FALSE);
    }

    /**
     * @expectedException        Framework\Database\Exception\Argument
     * @expectedExceptionMessage Invalid argument
     */
    public function testSettingFromPropertyException()
    {
        $this->query->from('');
    }

    public function testSettingFromProperty()
    {
        $instance = $this->query->from('user', array('name', 'id'));

        $_from = $this->reflector->getProperty('_from');
        $_from->setAccessible(TRUE);
        assertEquals('user', $_from->getValue($this->query));

        $_fields = $this->reflector->getProperty('_fields');
        $_fields->setAccessible(TRUE);
        $fields  = $_fields->getValue($this->query);
        assertEquals(array('name', 'id'), $fields['user']);

        assertInstanceOf('Framework\Database\Query', $instance);
    }

    /**
     * @expectedException        Framework\Database\Exception\Argument
     * @expectedExceptionMessage Invalid argument
     */
    public function testSettingJoinPropertyException()
    {
        $this->query->join('', 'on');
    }

    /**
     * @expectedException        Framework\Database\Exception\Argument
     * @expectedExceptionMessage Invalid argument
     */
    public function testSettingJoinOnPropertyException()
    {
        $this->query->join('user', '');
    }

    public function testSettingJoinProperty()
    {
        $instance = $this->query->join('user', 'comment', array(
            'username' => 'name',
            'comment'
        ));

        $_fields = $this->reflector->getProperty('_fields');
        $_fields->setAccessible(TRUE);
        $fields  = $_fields->getValue($this->query);

        assertEquals(array(
            'username' => 'name',
            'comment'
          ), $fields['user']);

        $_join = $this->reflector->getProperty('_join');
        $_join->setAccessible(TRUE);

        assertEquals($_join->getValue($this->query), "JOIN user ON comment");

        assertInstanceOf('Framework\Database\Query', $instance);
    }

}