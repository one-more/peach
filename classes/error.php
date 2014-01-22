<?php
/**
 * writes errors in the log file and displays an error message on the screen
 *
 * Class error
 *
 * @author Nikolaev D.
 */
class error {

    /**
     *  deletes a log file if its size is larger than 4 mb
     */
    public static function init()
    {
        if(filesize('..'.DS.'error.log') > 4096*1024) {
            unlink('..'.DS.'error.log');
        }
    }

    /**
     * @param $msg
     */
    public static function log($msg) {
        file_put_contents(SITE_PATH.'error.log',date('j.m.Y H:i:s').' - '.$msg."\r\n", FILE_APPEND);
    }

    /**
     * shows error message
     */
    public static function show_error()
    {
        echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], SITE_PATH.'html');
    }
}