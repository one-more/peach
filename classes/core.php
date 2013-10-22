<?php
class core {
    public static function initialise() {
        if(!file_exists('../configuration.txt')) {
            $siteController =  installer::getAdminController('site');

            $siteController->exec('display');

            die();
        }

        exceptionHandler::initialise();
    }
}