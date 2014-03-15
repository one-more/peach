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
            return $controller->exec('get', $id == null ? static::get_id() : $id);
        }
    }

    /**
     * @return bool
     */
    public static function get_id()
    {
        $mode = core::$mode;
        $method = "get_{$mode}_controller";
        $controller = static::$method('auth');

        return $controller->get_id();
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
        $mode = core::$mode;

        $method = "get_{$mode}_controller";

        $controller = static::$method('auth');

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
        if(!static::$path) {
            static::$path = SITE_PATH.'extensions'.DS.'user'.DS;
        }

        $mode = preg_split('/\//', $_REQUEST['old_url'])[1];

        $mode = $mode == 'admin' ? 'admin' : 'site';

        $admin_file     = static::$path.$_COOKIE['PHPSESSID'];

        if(file_exists($admin_file) && $mode == 'admin') {

            $arr = json_decode(file_get_contents($admin_file), true);

            return $arr['my_ip'];
        }
        elseif($mode == 'site') {

            $file = user::$path.'site'.DS.'session_files'.DS.$_COOKIE['PHPSESSID'];

            if(file_exists($file)) {
                $arr = json_decode(file_get_contents($file), true);

                return $arr['my_ip'];
            }
            else {
                return false;
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
            if(core::$mode == 'admin') {
                $file = static::$path.$_COOKIE['PHPSESSID'];

                $arr = json_decode(file_get_contents($file), true);

                if($_SESSION['admin_token'] == $arr['token']) {
                    return $_SESSION['admin_token'];
                }
                else {
                    return false;
                }
            }

            if(core::$mode == 'site') {
                if(!empty($_COOKIE['site_user'])) {
                    $cookie = $_COOKIE['site_user'];

                    $file = static::$path.'site'.DS.$cookie.DS.$_COOKIE['PHPSESSID'];
                    $arr  = json_decode(file_get_contents($file, true));

                    if($_SESSION['site_token'] == $arr['token']) {
                        return $_SESSION['site_token'];
                    }
                    else {
                        return false;
                    }
                }

                $file =
                    static::$path.'site'.DS.'session_files'.DS.$_COOKIE['PHPSESSID'];

                $arr = json_decode(file_get_contents($file), true);

                if($_SESSION['site_token'] == $arr['token']) {
                    return $_SESSION['site_token'];
                }
                else {
                    return false;
                }
            }
        }
        else {
            return false;
        }
    }

    public static function delete()
    {

    }
}