<?php
    session_start();

    ob_start();

    require_once '../classes/autoloader.php';
    require_once '../classes/defines.php';

    spl_autoload_register(['autoloader','load']);
    spl_autoload_register(['autoloader','loadExtension']);

    core::initialise();

    $document = factory::getDocument();

    router::route();

    $document->display();

    ob_end_flush();
