<?php

/**
 * ������ ������� ��������� Router'��
 */
return [
    '^test\.(html)$' => "./Client/TestController::client?extension=$1",
    "^test2.html$" => function(){
        echo 'TEST';
    }
];
