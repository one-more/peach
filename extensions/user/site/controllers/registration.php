<?php
namespace user_site;

/**
 * Class registrationcontroller
 *
 * @package user_site
 *
 * @author Nikolaev D.
 */
class registrationcontroller extends \supercontroller {

    /**
     *
     */
    public function display($arr)
    {
        $menu = \system::get_menu();
        $params = $menu::get_layout_params($arr['id']);

        $params = array_merge($params, \user::read_lang('registration_view'));

        if(\user::is_auth()) {
            return \templator::get_warning($params['already_registered']);
        }
        else {

            if($params['user_agreement']) {
                $text = file_get_contents($params['user_agreement']);
                $params['text'] = $text;
                $params['form'] = \templator::getTemplate(
                    'form',
                    $params,
                    \user::$path.'site'.DS.'views'.DS.'registration'
                );

                return \templator::getTemplate(
                    'agreement',
                    $params,
                    \user::$path.'site'.DS.'views'.DS.'registration'
                );
            }
            else {
                $params['form'] = \templator::getTemplate(
                    'form',
                    $params,
                    \user::$path.'site'.DS.'views'.DS.'registration'
                );

                return \templator::getTemplate(
                    'index',
                    $params,
                    \user::$path.'site'.DS.'views'.DS.'registration'
                );
            }

        }
    }

    /**
     * @return array
     */
    public function register()
    {
        if($_POST) {
            $model = \user::get_admin_model('user');

            $user = [
                'login'         =>  $_POST['login'],
                'password'      =>  $_POST['password'],
                'credentials'   => 'USER'
            ];

            $info = [
                'email'         =>  $_POST['email']
            ];

            $errors = $model->create($user, $info);

            if(is_array($errors)) {
                return ['error' => $errors];
            }
        }
        else {
            return 'empty post';
        }
    }
}