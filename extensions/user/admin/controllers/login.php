<?php
namespace user_admin;

/**
 * Class logincontroller
 *
 * @package user_admin
 *
 * @author Nikolaev D.
 *
 * View controller for login form
 */
class logincontroller extends \supercontroller implements \view_controller_interface {

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \user::read_lang('login_view');

        if(\system::get_menu() && \system::get_menu() != -1) {
            $params['action'] = 'index.php?class='.\system::get_menu().'&method=create_layout';

            $params['fieldset'] = '';
        }
        else {
            $params['action'] = '';

            $params['fieldset'] = \templator::get_warning($params['NO_MENU']);
        }

        $params['class']        = 'user';
        $params['controller']   = 'login';
        $params['extension']    = \user::get_info()['alias'];

        return \templator::getTemplate(
            'index',
            $params,
            \user::$path.'admin'.DS.'views'.DS.'login'
        );
    }

    /**
     * @return array|mixed
     */
    public function get_info()
    {
        return \user::read_lang('login_view');
    }
}