<?php

// PHP legacy and shames
include('Legacy.php');

// FileSystem class
include('FS.php');


spl_autoload_register(function($name){

    if(class_exists($name)) {
        return;
    }

    $name .= '.php';

    // Классы от Kernel
    if($path = \Kernel\FS::getKernel($name)) {
        return include($path);
    }

    // Классы от Root
    if($path = \Kernel\FS::getRoot($name)) {
        return include($path);
    }

    // Классы от UserLand
    if($path = \Kernel\FS::getUserLand($name)) {
        return include($path);
    }
});

// Подключаем Composer autoload, если таковой есть
if(file_exists('../vendor/autoload.php')){
    include('../vendor/autoload.php');
}