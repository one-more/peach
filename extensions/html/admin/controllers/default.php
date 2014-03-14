<?php

namespace html_admin;

/**
 * Class defaultcontroller
 *
 * @package html_admin
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
	{
        $params         = \html::read_lang('default_page');
        $params['js']   = \helper::get_jquery_tabs_js();
        $params['js'][] = \dom::create_element(
            'script',
            [
                'src'   => '/js/html/admin/views/default_view.js'
            ]
        );
        $params['js']   = \builder::build(
            'html_admin_default.js',
            $params['js'],
            false
        );

		return \templator::getTemplate(
            'index',
            $params,
            \html::$path.'admin'.DS.'views'.DS.'default'
        );
	}
} 
