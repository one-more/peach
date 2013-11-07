<?php
class core {
    public static function initialise() {

        exceptionHandler::initialise();

        //install site
        if(!file_exists('../configuration.ini')) {

            installer::start();

            exit;
        }
    }
}