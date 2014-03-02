<?php
namespace menu_admin;

/**
* Class defaultcontroller
* 
* namespace menu_admin
*
* @author Nikolaev D.	
*/
class defaultcontroller extends \supercontroller {
	
	/**
	* return string
	*/	
	public function display()
	{
		$params = \menu::read_lang('default_page');

        $params['js'] = \helper::get_jquery_tabs_js();
        $params['js'][] = \dom::create_element(
            'script',
            [
                'src' => '/js/menu/admin/layouts/default_layout.js'
            ]
        );

        $params['js'] = \builder::build('menu_admin_default.js', $params['js'], false);

        return \templator::getTemplate(
            'index',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'default'
        );
	}
} 
