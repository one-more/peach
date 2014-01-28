<?php
/**
 * server side routing
 *
 * Class router
 *
 * @author Nikolaev D.
 */
class router {
    /**
     * server side route
     */
    public static function route() {

        //if the user`s session is ended and started ajax request
        if(core::$mode == 'admin' && !empty($_SESSION['user']) && !user::is_auth()) {
            comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'loadPage',
                    'params'    => ['0' => '/admin']
                ]
            );

            return;
        }

		$class = preg_split('/\//', $_SERVER['REQUEST_URI'])[1];

        $class = preg_replace('/\?.*/', '', $class);

        $default = [
            'class'   =>    ($class == 'admin') ? 'admin' : 'site'
        ];

        $default = array_merge($default, $_REQUEST);

        $default['class']::start();
    }
}