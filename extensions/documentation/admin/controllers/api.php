<?php

namespace documentation_admin;

/**
 * Class apicontroller
 *
 * @package documentation_admin
 *
 * @author Nikolaev D.
 */
class apicontroller extends \supercontroller {

    public function display()
    {
        $params = [];
        $lang   = \system::get_current_lang();

        $path   = \documentation::$path.'admin'.DS.'views'.DS.$lang;

        if(!file_exists($path)) {
            $lang = 'ru-RU';
        }

        $path   = \documentation::$path.'admin'.DS.'views'.DS.$lang;

        $params['HEADER'] = \templator::getTemplate(
            'header',
            [],
            $path.DS.'default'
        );

        $path .= DS.'api';

        return \templator::getTemplate(
            'index',
            $params,
            $path
        );
    }
}