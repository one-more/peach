<?php
/**
 * initialize the CMS
 *
 * Class core
 *
 * @author Nikolaev D
 */
class core {

    /**
     * @var string - contains whether mode is now active: site or admin panel
     */
    public static $mode = 'site';

    public static function initialise() {

        mb_internal_encoding('UTF-8');

        mb_http_output('UTF-8');

        clearstatcache();

        exceptionHandler::initialise();

        builder::initialize();

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

        if($extension == 'admin') {
            static::$mode = 'admin';
        }
        else {
            static::$mode = 'site';
        }

        //install site
        if(!file_exists(SITE_PATH.'configuration.ini')) {

            installer::start();

            exit;
        }
    }
}