<?php
require_once '../application/common.php';
require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    $zfConfigArray
);

$application->bootstrap()
            ->run();
