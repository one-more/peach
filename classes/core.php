<?php
class core {

    /**
     * @var string - contains whether mode is now active: site or admin panel
     */
    public static $mode = 'site';

    public static function initialise() {

        exceptionHandler::initialise();

        $extension = preg_split('/\//', $_SERVER['REQUEST_URI'])[1];

        $class = !empty($_REQUEST['class'])? $_REQUEST['class'] : '';

        if(in_array($extension, ['admin', 'installer']) || in_array($class, ['admin', 'installer'])) {
            static::$mode = 'admin';
        }

        //install site
        if(!file_exists('../configuration.ini')) {

            installer::start();

            exit;
        }
    }
}