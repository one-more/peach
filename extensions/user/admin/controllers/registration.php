<?php
namespace user_admin;

/**
 * Class registrationcontroller
 *
 * @package user_admin
 *
 * @author Nikolaev D.
 *
 * view for user registration page
 */
class registrationcontroller extends \supercontroller implements \view_controller_interface {

    /**
     * @return string
     */
    public function display()
    {
        $params = \user::read_lang('registration_view');

        if(\system::get_menu() && \system::get_menu() != -1) {
            $menu = \system::get_menu();
            $params['action'] = 'index.php?class='.$menu.'&method=create_layout';
            $params['menu_html'] = $menu::get_create_layout_html();
        }
        else {
            $params['action'] = '';
            $params['menu_html'] = \templator::get_warning(
                \factory::get_reference('create_layout_fieldset')['NO_MENU']
            );
        }

        $params['class']        = 'user';
        $params['controller']   = 'registration';
        $params['extension']    = \user::get_info()['alias'];

        return \templator::getTemplate(
            'index',
            $params,
            \user::$path.'admin'.DS.'views'.DS.'registration'
        );
    }

    /**
     * @return array|mixed
     */
    public function get_info()
    {
        return \user::read_lang('registration_view');
    }
}