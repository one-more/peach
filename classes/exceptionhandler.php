<?php
/**
 * sets its own interceptors errors and exceptions
 *
 * Class exceptionHandler
 *
 * @author - Nikolaev Dmitriy
 */
class exceptionHandler {
    /**
     * register error handler functions
     */
    public static function initialise() {
        set_error_handler('peach_error_handler');

        set_exception_handler('peach_exception_handler');

        register_shutdown_function('peach_fatal_error_handler');
    }
}

/**
 *
 * custom exception handler
 *
 * @param $exception
 */
function peach_exception_handler($exception) {
    error::log($exception->getMessage());

    echo templator::getTemplate('error', ['error-msg'=>'an exception occurred'], '../html');
}


/**
 *
 * custom error handler
 *
 * @param $errno
 * @param $errstr
 * @param $errfile
 * @param $errline
 */
function peach_error_handler($errno, $errstr, $errfile, $errline) {

    $msg = "$errno : $errstr in $errline of $errfile";

    error::log($msg);

    echo templator::getTemplate('error', ['error-msg'=>'there was an error'], '../html');
}

/**
 * custom fatal error handler
 */
function peach_fatal_error_handler()
{
    if($arr = error_get_last()) {
        $msg = "FATAL ERROR : $arr[message] : $arr[line] : $arr[file] \r\n";

        $ds = DIRECTORY_SEPARATOR;

        file_put_contents(SITE_PATH.'error.log', $msg, FILE_APPEND);

        require_once(SITE_PATH.'classes'.$ds.'dom.php');

        echo dom::create_element('<link>', ['rel'=>'stylesheet', 'href' => '/css/bootstrap.min.css']);

        $str = file_get_contents(SITE_PATH.'html'.$ds.'error.html');

        echo preg_replace('/:error-msg/', 'fatal error occurred', $str);
    }
}