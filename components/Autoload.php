<?php

function __autoload($class_name)
{
    // список папок.
    $array_paths = array(
        '/models/',
        '/components/'
    );
    
    // подключаем 
    foreach ($array_paths as $path) {
        $path = ROOT . $path . $class_name . '.php';
        if (is_file($path)) {
            include_once $path;
        }
    }
}
