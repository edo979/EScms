<?php
//namespace Framework\Database\Query;
//
//class MysqlTest extends \PHPUnit_Framework_TestCase
//{
//    public function testMethodAll()
//    {
//        // Mock connector
//        $connector = $this->getMock('Framework\Database\Connector\Mysql', array('execute'));
//        
//        // Make object to test on
//        $Query = $this->getMock('Framework\Database\Query\Mysql', array(
//            '_buildSelect'
//        ));
//        $Query->expects($this->once())
//          ->method('_buildSelect');
//        
//        $connector->expects($this->once())
//          ->method('execute');
//        
//        // mock depedency connector to object
//        $Query->connector = $connector;
//        
//        $Query->all();
//    }
//}