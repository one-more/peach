<?php
    session_start();

    require_once '../classes/autoloader.php';
    require_once '../classes/defines.php';

    spl_autoload_register(['autoloader','load']);
	spl_autoload_register(['autoloader', 'loadTrait']);
    spl_autoload_register(['autoloader','loadExtension']);
    spl_autoload_register(['autoloader','loadInterface']);
    spl_autoload_register(['autoloader','loadTemplate']);
    spl_autoload_register(['autoloader','loadEditor']);

    core::initialise();

    router::route();
