<?php
class router {
    public static function route() {

		$class = preg_split('/\//', $_SERVER['REQUEST_URI'])[1];

        $default = [
            'class'   =>    $class ? $class : 'site'
        ];

        $default = array_merge($default, $_REQUEST);

        $default['class']::start();
    }
}