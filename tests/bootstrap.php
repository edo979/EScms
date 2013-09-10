<?php
$paths = explode(PATH_SEPARATOR, get_include_path());

require __DIR__.'/../vendor/autoload.php';
require $paths[1]
 .DIRECTORY_SEPARATOR
 .'PHPUnit'
 .DIRECTORY_SEPARATOR
 .'Framework'
 .DIRECTORY_SEPARATOR
 .'Assert'
 .DIRECTORY_SEPARATOR
 .'Functions.php';