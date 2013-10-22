<?php
    session_start();

    require_once '../classes/autoloader.php';
    require_once '../classes/defines.php';

    spl_autoload_register(['autoloader','load']);
    spl_autoload_register(['autoloader','loadExtension']);

    $document = factory::getDocument();

    core::initialise();

    router::route();

    $document->display();
