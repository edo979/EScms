<?php

namespace Framework\Database\Connector;

use Framework\Database as Database;
use Framework\Database\Exception as Exception;

/**
 * Description of Mysql
 *
 * @author Comp
 */
class Mysql extends Database\Connector
{

    protected $_service;

    /**
     * @readwrite
     */
    protected $_host;

    /**
     * @readwrite
     */
    protected $_username;

    /**
     * @readwrite
     */
    protected $_password;

    /**
     * @readwrite
     */
    protected $_schema;

    /**
     * @readwrite
     */
    protected $_port = "3306";

    /**
     * @readwrite
     */
    protected $_charset = "utf8";

    /**
     * @readwrite
     */
    protected $_engine = "InnoDB";

}