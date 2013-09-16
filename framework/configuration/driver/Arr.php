<?php

namespace Framework\Configuration\Driver;

use Framework\Configuration as Configuration;
use Framework\Configuration\Exception as Exception;

/**
 * Parse settings array
 */
class Arr extends Configuration\Driver
{

    public function parse($path)
    {
        if (!isset($this->_parsed[$path]))
        {
            if (empty($path))
            {
                throw new Exception\Argument("\$path argument is not valid");
            }

            ob_start();
            $settings = array();
            include("{$path}.php");
            ob_end_clean();

            $this->_parsed[$path] = $settings;
        }

        return $this->_parsed[$path];
    }

}