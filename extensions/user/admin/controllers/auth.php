<?php
namespace user_admin;
/**
 * Class authcontroller
 *
 * @author = Nikolaev D.
 */
class authcontroller extends \supercontroller {
    use \trait_extension_controller;

    /**
     * @var string
     */
    private  $extension;

    /**
     * @var
     */
    private $_cache_path;

    public function __construct()
    {
        $this->extension = 'user';

        \user::$path = '..'.DS.'extensions'.DS.'user'.DS;

        $this->_cache_path = '..'.DS.'extensions'.DS.'user'.DS.'admin'.DS.'cache'.DS;
    }

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = $this->getLang('auth_page');

        $params['css']  = array_merge(\document::$css_files,
            ['<link rel="stylesheet" href="/css/user/admin/auth.css" />']);

        $params['js']   = array_merge(\document::$js_files,
            [
                '<script src="/js/user/admin/views/auth_view.js"></script>',
                '<script src="/js/empty_router.js"></script>'
            ]);

        $params['css'] = \builder::build('admin_auth.css', $params['css']);
        $params['js']  = \builder::build('admin_auth.js', $params['js']);

        return \templator::getTemplate('index', $params ,\user::$path.'admin'.DS.'views'.DS.'auth');
    }

    /**
     * return boolean
     */
    public function is_auth()
    {
        if(!empty($_SESSION['user']))
        {
            $ini = \factory::getIniServer(\user::$path.'user.ini');

            $interval = $ini->read('params', 'exit_time', 15);

            $activity = $ini->read('user', 'last_activity', time());

            if((time() - $activity) > 60*$interval) {
                unset($_SESSION['user']);
            }
            else {
                $ini->write('user', 'last_activity', time());

                $ini->updateFile();
            }
        }

        return !empty($_SESSION['user']);
    }

    /**
     * @return array
     */
    public function auth()
    {
        if($_POST) {
            $defaults = [
                'login'     => 'nodata',
                'password'  => 'nodata'
            ];

            $data = array_merge($defaults, $_POST);

            $model = \user::get_admin_model('auth');

            $error = $model->auth($data);

            if(is_array($error))
            {
                return ['error' => $error];
            }
            else {
                $ini = \factory::getIniServer(\user::$path.'user.ini');

                $url = $ini->read('auth', 'redirect_url', '/');

                $_SESSION['user'] = $error;

                $model = \user::get_admin_model('user');

                $user = $model->get($error);

                $this->set_cache_view('user_'.$error, json_encode($user));

                $ini = \factory::getIniServer(\user::$path.'user.ini');

                $ini->write('user', 'last_activity', time());

                $ini->updateFile();

                return ['error' => '', 'url' => $url];
            }
        }
        else {
            return ['error' => 'no data'];
        }
    }

    /**
     * exit from admin panel
     */
    public function leave()
    {
        unset($_SESSION['user']);
    }
}