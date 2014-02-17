<?php
/**
 * extension to control the parameters of the system
 *
 * Class system
 *
 * @author Nikolaev D.
 */
class system {
    use trait_extension;

    /**
     * @var array
     */
    public  static  $system_js = [
        '<script src="/js/system/admin/models/system_model.js"></script>'
    ];

    /**
     * @var array
     */
    public static $system_site_js = [
        '<script src="/js/system/site/models/system_model.js"></script>'
    ];

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

    /**
     * @return string
     */
    public static function get_version()
    {
        return '1.5';
    }

    /**
     * @param $type
     * @param $version
     * @return bool
     */
    public static function accept_cms_version($type, $version)
    {
        switch($type) {
            case 'extension':
                return true;
                break;
            case 'template':
                return true;
                break;
        }
    }
}