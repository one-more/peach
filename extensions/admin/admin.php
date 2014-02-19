<?php
/**
 * administrative part of CMS
 *
 * Class admin
 *
 * @author Nikolaev D.
 */
class admin {
    use trait_extension
    {
        trait_extension::start as trait_start;
    }

    /**
     * start the user panel
     */
    public static function start()
    {
        if(!user::is_auth() || !user::is_admin()) {
            user::auth('/admin');
        }
        else {
            static::trait_start();
        }
    }

    /**
     * @return array
     */
    public static function get_widgets() {
        $arr = installer::get_extensions();

        $tmp = [];

        foreach($arr as $el) {
            $tmp[] = $el['name'];
        }

        $arr = $tmp;

        $arr = array_merge($arr, ['user', 'installer']);

        $result = [];

        foreach($arr as $el) {
            if(new $el instanceof widget_extension_interface) {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_user_extensions()
    {
        $arr = installer::get_extensions();

        $result = [];

        foreach($arr as $el) {
            if(new $el['name'] instanceof user_extension_interface) {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_site_templates()
    {
        $arr = installer::get_templates();

        $result = [];

        foreach($arr as $el) {
            if($el['type'] == 'site' && new $el['name'] instanceof site_template_interface) {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_admin_templates()
    {
        $arr = installer::get_templates();

        $result = [];

        foreach($arr as $el) {
            if($el['type'] == 'admin' && new $el['name'] instanceof admin_template_interface) {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_menu_extensions()
    {
        $arr = installer::get_extensions();

        $result = [];

        foreach($arr as $el) {
            if(new $el['name'] instanceof menu_extension_interface) {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_editors()
    {
        $arr = installer::get_editors();

        $result = [];

        foreach($arr as $el) {
            if(new $el['name'] instanceof editor_interface) {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_site_daemons()
    {
        $arr = installer::get_daemons();

        $result = [];

        foreach($arr as $el) {
            if(new $el['name'] instanceof daemon_extension_interface &&
                $el['type'] == 'site') {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_admin_daemons()
    {
        $arr = installer::get_daemons();

        $result = [];

        foreach($arr as $el) {
            if(new $el['name'] instanceof daemon_extension_interface &&
                $el['type'] == 'admin') {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function get_daemons()
    {
        $arr = installer::get_daemons();

        $result = [];

        foreach($arr as $el) {
            if(new $el['name'] instanceof daemon_extension_interface) {
                $result[] = $el;
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public static function get_template()
    {
        return static::read_params('options')['template'];
    }
}