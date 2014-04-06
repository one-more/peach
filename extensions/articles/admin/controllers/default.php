<?php

namespace articles_admin;

/**
 * Class defaultcontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
	{
		$params = \articles::read_lang('default_page');

        $params['js'] = \helper::get_jquery_tabs_js();

        $params['js'][] = \dom::create_element(
            'script',
            [
                'src' => '/js/articles/admin/views/default_view.js'
            ]
        );

        $params['js'] = \builder::build(
            'articles_admin_default_js.js',
            $params['js'],
            false
        );

        return \templator::getTemplate(
            'index',
            $params,
            \articles::$path.'admin'.DS.'views'.DS.'default'
        );
	}
} 
