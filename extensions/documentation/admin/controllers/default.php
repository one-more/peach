<?php

namespace documentation_admin;
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller{
    /**
     * @return mixed|string
     */
    public function display()
    {
        $lang = \system::get_current_lang();

        if(!file_exists(\documentation::$path.'admin'.DS.'views'.DS.$lang)) {
            $lang = 'ru-RU';
        }

        $path = \documentation::$path.'admin'.DS.'views'.DS.$lang.DS.'default';
        $params = [];
        $params['HEADER'] = \templator::getTemplate(
            'header',
            [],
            $path
        );

        return \templator::getTemplate(
            'index',
            $params,
            $path
        );
    }
}