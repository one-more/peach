<?php

namespace tablepagination_admin;

/**
 * Class defaultcontrollers
 *
 * @package tablepagination_admin
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \tablepagination::read_lang('default_page');

        return \templator::getTemplate(
            'index',
            $params,
            \tablepagination::$path.'admin'.DS.'views'.DS.'default'
        );
    }
}