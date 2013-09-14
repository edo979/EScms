<?php

// constants

define("DEBUG", TRUE);
define("APP_PATH", dirname(__DIR__));

try
{
    
}
catch (Exception $e)
{
    // list exceptions

    $exceptions = array(
        "500" => array(
            "Framework\Core\Exception",
            "Framework\Core\Exception\Argument",
            "Framework\Core\Exception\Implementation",
            "Framework\Core\Exception\Property",
            "Framework\Core\Exception\ReadOnly",
            "Framework\Core\Exception\WriteOnly",
        )
    );

    $exception = get_class($e);

    print_r($e);

    // attempt to find the approapriate template, and render

    foreach ($exceptions as $template => $classes)
    {
        foreach ($classes as $class)
        {
            if ($class == $exception)
            {
                header("Content-type: text/html");
                include(APP_PATH . "/application/views/errors/{$template}.php");
                exit;
            }
        }
    }

    // render fallback template

    header("Content-type: text/html");
    echo "An error occurred.";
    exit;
}