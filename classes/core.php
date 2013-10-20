<?php
class core {
    public static function initialise() {
        if(!file_exists('../configuration.txt')) {
            $siteController =  installer::getController('site');

            $siteController->exec('display');

            die();
        }
    }
}