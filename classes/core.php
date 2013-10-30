<?php
class core {
    public static function initialise() {

        exceptionHandler::initialise();

        //install site
        if(!file_exists('../configuration.ini')) {

            if(($loc = preg_split('/\//', $_SERVER['REQUEST_URI'])[1]) != 'install') {
                header('Location: /install');
            }

            installer::installSite();

            ob_end_flush();

            exit;
        }
    }
}