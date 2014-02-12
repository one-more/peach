<?php
/**
 * extension for managing users
 *
 * Class user
 *
 * @author Nikolaev D.
 */
class user implements widget_extension_interface {
    use trait_extension, trait_widget_extension;

    /**
     * @var bool
     */
    private static $clear_cache = true;

    /**
     * @param $user
     * @param $info
     */
    public static function create($user, $info)
    {
        $controller = static::get_admin_controller('user');

        return $controller->exec('create', ['user'=>$user, 'info'=>$info]);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public static function get($id = null)
    {
        $controller = static::get_admin_controller('user');

        if($id == null && !static::is_auth()) {
            return false;
        }
        else {
            return $controller->exec('get', $id == null ? $_SESSION['user'] : $id);
        }
    }

    /**
     * @return bool
     */
    public static function get_id()
    {
        return static::is_auth() ? $_SESSION['user'] : false;
    }

    /**
     * @param $url
     */
    public static function auth($url)
    {
        switch(core::$mode) {
            case 'admin' :

                $ini = factory::getIniServer('..'.DS.'extensions'.DS.'user'.DS.'user.ini');

                $ini->write('auth', 'redirect_url', $url);

                $controller = static::get_admin_controller('auth');

                echo $controller->exec('display');

                break;
        }
    }

    /**
     * @return boolean
     */
    public static function is_auth()
    {
        $controller = static::get_admin_controller('auth');

        return $controller->is_auth();
    }

    /**
     * @return boolean
     */
    public static function is_super_admin()
    {
        $controller = static::get_admin_controller('user');

        return $controller->is_super_admin();
    }

    /**
     * @return boolean
     */
    public static function is_admin()
    {
        $controller = static::get_admin_controller('user');

        return $controller->is_admin();
    }

    /**
     * @return array
     */
    public static  function get_info()
    {
        $alias = static::read_lang('info')['alias'];

        return ['alias'=>$alias, 'icon'=>null, 'submenu'=>null];
    }

    /**
     * @return array|bool|string
     */
    public static function get_ip()
    {
        $ini = factory::getIniServer(static::$path.'user.ini');

        $interval = $ini->read('user', 'exit_time', 15);

        if(!static::$path) {
            static::$path = SITE_PATH.'extensions'.DS.'user'.DS;
        }

        if(file_exists(static::$path.'my_cookies')) {

            if(time() > (filemtime(static::$path.'my_cookies') + 60*$interval)) {
                $arr = json_decode(file_get_contents(static::$path.'my_cookies'), true);

                unlink(static::$path.'my_cookies');

                return $arr['my_ip'];
            }
            else {
                $arr = json_decode(file_get_contents(static::$path.'my_cookies'), true);

                return $arr['my_ip'];
            }
        }
        else {

            return false;
        }
    }

    /**
     * @return array|bool|string
     */
    public static function get_token()
    {
        if(static::is_auth()) {
            return $_SESSION['token'];
        }
        else {
            return false;
        }
    }
}