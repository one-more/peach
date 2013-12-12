<?php

/**
 * Class system
 *
 * @author Nikolaev D.
 */
class system {
    use trait_extension;

    /**
     * @return array
     */
    public static function get_languages()
    {
        $controller = static::get_admin_controller('lang');
        $arr = $controller->exec('get_list');

        return is_array($arr)? $arr : json_decode($arr, true);
    }

    /**
     * @return string
     */
    public static function get_current_lang()
    {
        $ini = factory::getIniServer();

        return $ini->read('language', 'current', false);
    }

    /**
     * @return mixed
     */
    public static function get_menu()
    {
        return static::read_params('options')['menu'];
    }
}