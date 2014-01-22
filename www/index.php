<?php
/**
 * PEACH CMS
 *
 * version V1.5
 *
 * @author Nikolaev D
 */
error_reporting(0);

session_start();

require_once '../classes/defines.php';
require_once '../classes/autoloader.php';

spl_autoload_register(['autoloader','load']);
spl_autoload_register(['autoloader', 'loadTrait']);
spl_autoload_register(['autoloader','loadInterface']);
spl_autoload_register(['autoloader','loadTemplate']);
spl_autoload_register(['autoloader','loadEditor']);
spl_autoload_register(['autoloader','loadExtension']);

core::initialise();

router::route();
