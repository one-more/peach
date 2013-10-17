<?php
    require_once 'classes/autoloader.php';
    require_once 'classes/defines.php';

    spl_autoload_register(['autoloader','load']);

    core::initialise();

    router::route();

    $document = factory::getDocument();

    $document->display();
