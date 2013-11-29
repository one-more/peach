<?php
/**
 * Class router
 *
 * @author Nikolaev D.
 */
class router {
    /**
     * server side route
     */
    public static function route() {

		$class = preg_split('/\//', $_SERVER['REQUEST_URI'])[1];

        $class = preg_replace('/\?.*/', '', $class);

        $default = [
            'class'   =>    $class ? $class : 'site'
        ];

        $default = array_merge($default, $_REQUEST);

        $default['class']::start();
    }
}