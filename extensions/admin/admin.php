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

    /**
     * @return string
     */
    public static function get_daemons_js()
    {
        header('Content-type: application/javascript');

        $result = [];

        $mode = core::$mode;

        $method = "get_{$mode}_daemons";

        $arr = static::$method();

        if(core::$mode == 'admin') {
            $arr = array_merge($arr, [ ['name' => 'link_corrector'] ]);
        }

        foreach($arr as $el) {
            $result = array_merge($result, $el['name']::get_js());
        }

        if(count($result) > 0) {
            $src = builder::build($mode.'_daemons.js', $result, false);

            $src = '.'.$src;

            echo file_get_contents($src);
        }
        else {
            echo '';
        }
    }

    /**
     * @return string
     */
    public static function get_editor_js()
    {
        header('Content-type: application/javascript');

        $editor = static::read_params('options', 'editor');

        $arr = $editor::get_js();

        if(count($arr) > 0) {
            $src = builder::build($editor.'.js', $arr, false);

            $src = '.'.$src;

            echo file_get_contents($src);
        }
        else {
            echo '';
        }
    }

    /**
     * @return string
     */
    public static function get_editor_css()
    {
        header('Content-type: text/css');

        $editor = static::read_params('options', 'editor');

        $arr = $editor::get_css();

        if(count($arr) > 0) {
            $src = builder::build($editor.'.css', $arr, false);

            echo $src;
        }
        else {
            echo '';
        }
    }
}