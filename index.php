<?php

session_start();
//ini_set('display_errors', 'off');
error_reporting(E_ALL);

ob_start();
include("./Kernel/bootstrap.php");
ob_end_clean();

$kernel = new \Kernel\Kernel();
$kernel->getRouter()->routeV2();