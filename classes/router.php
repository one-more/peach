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
        if(!empty($_REQUEST['ajax'])) {

            if(core::$mode == 'admin' && !user::is_auth()) {

                //todo - костыль
                if(!in_array($_REQUEST['class'], ['noop'])) {
                    if(empty($_REQUEST['task']) || $_REQUEST['task'] != 'auth') {

                        comet::add_message(
                            [
                                'task'      => 'delegate',
                                'object'    => 'App',
                                'method'    => 'loadPage',
                                'params'    => ['/admin']
                            ]
                        );

                        return;
                    }
                }
            }
        }

		$class = preg_split('/\//', $_SERVER['REQUEST_URI'])[1];

        $class = preg_replace('/\?.*/', '', $class);

        $default = [
            'class'   =>    ($class == 'admin') ? 'admin' : 'site'
        ];

        $default = array_merge($default, $_REQUEST);

        if(class_exists($default['class']))
            $default['class']::start();
        else {
            $ref = factory::get_reference('errors')['no_extension'];

            echo templator::get_warning($ref).dom::create_element(
                    'img',
                    [
                        'class' => 'width-100 adapt-height',
                        'src'   => '/media/images/404.jpg'
                    ]
                );
        }
    }
}