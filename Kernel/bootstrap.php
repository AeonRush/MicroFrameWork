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

    // ������ �� Kernel
    if($path = \Kernel\FS::getKernel($name)) {
        return include($path);
    }

    // ������ �� Root
    if($path = \Kernel\FS::getRoot($name)) {
        return include($path);
    }

    // ������ �� UserLand
    if($path = \Kernel\FS::getUserLand($name)) {
        return include($path);
    }
});

// ���������� Composer autoload, ���� ������� ����
if(file_exists('../vendor/autoload.php')){
    include('../vendor/autoload.php');
}