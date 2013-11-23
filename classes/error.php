<?php
/**
 * Class error
 *
 * @author Nikolaev D.
 */
class error {
    /**
     * @param $msg
     */
    public static function log($msg) {
        file_put_contents('../error.log',date('j.m.Y H:i:s').' - '.$msg."\r\n", FILE_APPEND);
    }

    /**
     * shows error message
     */
    public static function show_error()
    {
        echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], '../html');
    }
}