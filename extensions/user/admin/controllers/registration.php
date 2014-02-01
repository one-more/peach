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
            $params['action'] = 'index.php?class='.system::get_menu().'&method=create_layout';
        }
        else {
            $params['action'] = '';
        }

        $params['fieldset'] = \helper::get_create_layout_fieldset();

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