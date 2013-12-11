<?php
class core {

    /**
     * @var string - contains whether mode is now active: site or admin panel
     */
    public static $mode = 'site';

    public static function initialise() {

        mb_internal_encoding('UTF-8');

        mb_http_output('UTF-8');

        exceptionHandler::initialise();

        error::init();

        //todo костыль
        if(empty($_REQUEST['ajax']))
        {
            $extension = preg_split('/\//', $_SERVER['REQUEST_URI'])[1];
        }
        elseif(!empty($_REQUEST['old_url'])) {
            $extension = preg_split('/\//', $_REQUEST['old_url'])[1];
        }
        else {
            $extension = '';
        }

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