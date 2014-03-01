<?php
namespace link_corrector_admin;

/**
 * Class defaultcontroller
 *
 * @package link_corrector\admin
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \link_corrector::read_lang('default_page');

        return \templator::getTemplate(
            'index',
            $params,
            \link_corrector::$path.'admin'.DS.'views'.DS.'default'
        );
    }
}