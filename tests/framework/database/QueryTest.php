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
        $this->_mock = null;
        $this->query = null;
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
        $this->mock->expects($this->any())
          ->method('escape')
          ->with('test')
          ->will($this->returnValue("escaped"));

        $_quote = $this->reflector->getMethod('_quote');
        $_quote->setAccessible(TRUE);

        // call _quote with string
        $escaped = $_quote->invoke($this->query, 'test');
        assertEquals("'escaped'", $escaped);

        // call _quote with array
        $escaped = $_quote->invoke($this->query, array('test', 'test'));
        assertEquals("'escaped', 'escaped'", $escaped);

        // call _quote with null
        $escaped = $_quote->invoke($this->query, NULL);
        assertEquals("NULL", $escaped);

        // call _quote with boolean
        $escaped = $_quote->invoke($this->query, FALSE);
        assertEquals(0, $escaped);
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
        $fields = $_fields->getValue($this->query);
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
        $fields = $_fields->getValue($this->query);

        assertEquals(array(
            'username' => 'name',
            'comment'
          ), $fields['user']);

        $_join = $this->reflector->getProperty('_join');
        $_join->setAccessible(TRUE);

        assertEquals($_join->getValue($this->query), array("JOIN user ON comment"));

        assertInstanceOf('Framework\Database\Query', $instance);
    }

    /**
     * @expectedException        Framework\Database\Exception\Argument
     * @expectedExceptionMessage Invalid argument
     */
    public function testSettingLimitPropertyException()
    {
        $this->query->limit('');
    }

    public function testSettingLimitProperty()
    {
        $instance = $this->query->limit(5, 3);

        $_limit = $this->reflector->getProperty('_limit');
        $_limit->setAccessible(TRUE);

        assertEquals($_limit->getValue($this->query), 5);

        $_offset = $this->reflector->getProperty('_offset');
        $_offset->setAccessible(TRUE);

        assertEquals($_offset->getValue($this->query), 10);

        assertInstanceOf('Framework\Database\Query', $instance);
    }

    /**
     * @expectedException        Framework\Database\Exception\Argument
     * @expectedExceptionMessage Invalid argument
     */
    public function testSettingOrderPropertyException()
    {
        $this->query->order('');
    }

    public function testSettingOrderProperty()
    {
        $instance = $this->query->order('user');

        $_order = $this->reflector->getProperty('_order');
        $_order->setAccessible(TRUE);

        assertEquals($_order->getValue($this->query), 'user');

        $_direction = $this->reflector->getProperty('_direction');
        $_direction->setAccessible(TRUE);

        assertEquals($_direction->getValue($this->query), 'asc');

        assertInstanceOf('Framework\Database\Query', $instance);
    }

    /**
     * @expectedException        Framework\Database\Exception\Argument
     * @expectedExceptionMessage Invalid argument
     */
    public function testSettinWhererPropertyException()
    {
        $this->query->where();
    }

    public function testSettingWhererProperty()
    {
        // Stub escepe method in connector class
        $this->mock->expects($this->any())
          ->method('escape')
          ->will($this->returnValue('clean'));

        $instance = $this->query->where(
          'user = ? and comment = ?', 'john', 'comment'
        );

        $_where = $this->reflector->getProperty('_where');
        $_where->setAccessible(TRUE);

        // stub will return 'clean'
        assertEquals(array("user = 'clean' and comment = 'clean'"), $_where->getValue($this->query));
        
        assertInstanceOf('Framework\Database\Query', $instance);
    }

    public function testBuildingSelectQueries()
    {
        // Stub escepe method in connector class
        $this->mock->expects($this->any())
          ->method('escape')
          ->will($this->returnValue('clean'));

        // set query parameters
        $this->query->from('user', array('id', 'username'));
        $this->query->join('comment', 'user', array(
            'comment.user_id' => 'user',
            'comment'
        ));
        $this->query->where('user = id'); // use stub
        $this->query->where('user = ? AND id = ?', 'john', 1); // use stub
        $this->query->order('user', 'DESC');
        $this->query->limit(5, 3);

        // build query
        $_select = $this->reflector->getMethod('_buildSelect');
        $_select->setAccessible(TRUE);
        $selectQuery = $_select->invoke($this->query);

        assertEquals(
          "SELECT id, username, comment.user_id AS user, comment FROM user JOIN comment ON user WHERE user = id AND user = 'clean' AND id = clean ORDER BY user DESC LIMIT 5, 10"
          , $selectQuery);
    }

    public function testBuildingInsertQueries()
    {
        // Stub escepe method in connector class
        $this->mock->expects($this->any())
          ->method('escape')
          ->will($this->returnValue('clean'));

        // set query parameters
        $data = array(
            'username' => 'john',
            'password' => 'secret'
        );
        $this->query->from('user');

        // build query
        $_insert = $this->reflector->getMethod('_buildInsert');
        $_insert->setAccessible(TRUE);
        $insertQuery = $_insert->invoke($this->query, $data);

        assertEquals("INSERT INTO `user` (`username', 'password`) VALUES ('clean', 'clean')", $insertQuery);
    }

    public function testBuildingUpdateQueries()
    {
        // Stub escepe method in connector class
        $this->mock->expects($this->any())
          ->method('escape')
          ->will($this->returnValue('clean'));

        // set query parameters
        $data = array(
            'username' => 'john',
            'password' => 'secret'
        );
        $this->query->from('user')
          ->where('user = ?', 1)
          ->limit(1);

        // build query
        $_update = $this->reflector->getMethod('_buildUpdate');
        $_update->setAccessible(TRUE);
        $updateQuery = $_update->invoke($this->query, $data);

        assertEquals("UPDATE user SET username = 'clean', password = 'clean' WHERE user = clean LIMIT 1 0", $updateQuery);
    }

}