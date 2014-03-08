<?php
namespace user_site;

/**
 * Class authcontroller
 *
 * @package user_site
 *
 * @author Nikolaev D.
 */
class authcontroller extends \supercontroller {

    /**
     * @return bool
     */
    public function get_id()
    {
        if(!empty($_COOKIE['site_user'])) {
            return $_COOKIE['site_user'];
        }
        elseif(!empty($_SESSION['site_user'])) {
            return $_SESSION['site_user'];
        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function is_auth()
    {
        if(!empty($_COOKIE['site_user'])) {
            $file = \user::$path.'site_'.$_COOKIE['PHPSESSID'];
            $sf = \user::read_params('user_session_file');

            if($sf[0] != $file) {
                unlink($sf[0]);

                file_put_contents(
                    $file,
                    json_encode(['my_ip' => getenv('REMOTE_ADDR')])
                );

                \user::write_params('user_session_file', [$file]);
            }
        }

        return (!empty($_COOKIE['site_user']) || !empty($_SESSION['site_user']));
    }

    /**
     *
     */
    public function auth()
    {
        $model  = \user::get_site_model('auth');
        $lang   = \user::read_lang('auth');

        $errors = $model->auth($_POST);

        if(is_array($errors)) {
            return ['error' => $errors];
        }
        elseif($errors) {
            $rem = !empty($_POST['remember_me']) ? 1 : 0;

            if($rem == 1) {
                setcookie('site_user', $errors, 0);
            }
            else {
                $_SESSION['site_user'] = $errors;
            }

            $file = \user::$path.'site_'.$_COOKIE['PHPSESSID'];
            $sf = \user::read_params('user_session_file');
            if(file_exists($sf[0])) {
                unlink($sf[0]);
            }

            file_put_contents(
                $file,
                json_encode(['my_ip' => getenv('REMOTE_ADDR')])
            );
            \user::write_params('user_session_file', [$file]);

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'reload',
                    'params'    => []
                ],
                getenv('REMOTE_ADDR')
            );
        }
        else {
            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => [$lang['wrong'], 'error']
                ],
                getenv('REMOTE_ADDR')
            );
        }
    }

    /**
     *
     */
    public function leave()
    {
        if(!empty($_COOKIE['site_user'])) {
            unset($_COOKIE['site_user']);
        }

        if(!empty($_SESSION['site_user'])) {
            unset($_SESSION['site_user']);
        }

        $sf = \user::read_params('user_session_file');

        if(file_exists($sf[0])) {
            unlink($sf[0]);
        }

        \user::write_params('user_session_file', []);

        if(!empty($_REQUEST['ajax'])) {
            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'reload',
                    'params'    => []
                ],
                getenv('REMOTE_ADDR')
            );
        }
    }
}