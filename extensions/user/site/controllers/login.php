<?php
namespace user_site;

/**
 * Class logincontroller
 *
 * @package user_site
 *
 * @author Nikolaev D.
 */
class logincontroller extends \supercontroller {

    /**
     *
     */
    public function display($params)
    {
        if(\user::is_auth()) {
            $params = \user::read_lang('login_form');

            $user = json_decode(\user::get(), true);
            $user = array_merge($user['user'], $user['info']);
            $params['login'] = $user['login'];

            return \templator::getTemplate(
                'entry',
                $params,
                \user::$path.'site'.DS.'views'.DS.'login'
            );
        }
        else {
            $menu = \system::get_menu();

            $arr = $menu::get_layout_params($params['id']);

            $params = array_merge(\user::read_lang('login_form'), $arr);

            return \templator::getTemplate(
                'index',
                $params,
                \user::$path.'site'.DS.'views'.DS.'login'
            );
        }
    }
}