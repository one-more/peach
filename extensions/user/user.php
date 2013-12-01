<?php
/**
 * Class user
 *
 * @author Nikolaev D.
 */
class user implements widget_extension_interface {
    use trait_extension, trait_widget_extension;

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
}