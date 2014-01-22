<?php
namespace user_admin;

/**
 * Class defaultcontroller
 * @package user_admin
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {
    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \user::read_lang('default_page');

        $params['js'] = \helper::get_jquery_tabs_js();

        $params['js'][] = \dom::create_element('script',
            ['src'=>'/js/user/admin/views/user_view.js']);

        $params['js'] = \builder::build('user_admin_default.js', $params['js'], false);

        return \templator::getTemplate(
            'index',
            $params,
            \user::$path.'admin'.DS.'views'.DS.'default'
        );
    }
}