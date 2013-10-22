<?php
/**
 * Class exceptionHandler
 *
 * @author - Nikolaev Dmitriy
 */
class exceptionHandler {
    public static function initialise() {
        set_error_handler('peach_error_handler');

        set_exception_handler('peach_exception_handler');
    }
}

function peach_exception_handler($exception) {
    error::log($exception->getMessage());

    echo templator::getTemplate('error', ['error-msg'=>'an exception occurred'], '../html');
}

function peach_error_handler($errno, $errstr, $errfile, $errline) {

    $msg = "$errno : $errstr in $errline of $errfile";

    error::log($msg);

    echo templator::getTemplate('error', ['error-msg'=>'there was an error'], '../html');
}