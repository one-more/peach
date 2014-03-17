<?php

namespace documentation_admin;

/**
 * Class langcontroller
 *
 * @package documentation_admin
 *
 * @author Nikolaev D.
 */
class langcontroller extends \supercontroller {

    /**
     * @return mixed|string
     */
    public function display()
    {
        $lang = \system::get_current_lang();

        $path = \documentation::$path.'admin'.DS.'views'.DS;

        if(!file_exists($path.$lang)) {
            $lang = 'ru-RU';
        }

        $params = [];
        $params['HEADER'] = \templator::getTemplate(
            'header',
            [],
            $path.$lang.DS.'default'
        );

        return \templator::getTemplate(
            'index',
            $params,
            $path.$lang.DS.'lang'
        );
    }
}