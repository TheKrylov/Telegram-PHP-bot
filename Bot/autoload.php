<?php


function my_app_autoload($class)
{



    $classParts = explode('\\', $class);



    $classParts[0] = __DIR__;

    $path = implode(DIRECTORY_SEPARATOR, $classParts) . '.php';

    

    if (file_exists($path)) {
        require $path;
    }
    //echo "$path<br>";

}
spl_autoload_register('my_app_autoload');
