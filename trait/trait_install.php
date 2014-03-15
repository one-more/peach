<?php

/**
 * Class trait_install
 *
 * requires static variable $path
 */
trait trait_install {

    /**
     * @throws Exception
     */
    public static function install_sql()
    {
        if(empty(static::$path)) {
            throw new Exception('variable path is empty! in trait_install');
        }

        $model  = static::get_admin_model('default');
        $sql    = helper::getSql(
            static::$path.'admin'.DS.'resources'.DS.'install.sql'
        );

        foreach($sql as $el) {
            $model->execute($el);
        }
    }

    /**
     * @param $langs
     * @throws Exception
     */
    public static function install_reference($langs)
    {
        if(empty(static::$path)) {
            throw new Exception('variable path is empty! in trait_install');
        }

        $ini = factory::getIniServer(
            static::$path.'admin'.DS.'resources'.DS.'lang_model.ini'
        );

        foreach($langs as $el) {
            $path = SITE_PATH.'lang'.DS.'references'.DS."{$el}.ini";

            if(file_exists($path)) {
                $ref    = factory::getIniServer($path);
                $arr1   = $ini->readSection($el);
                $arr2   = $ref->readSection('lang_model');
                $arr2   = array_merge($arr2, $arr1);
                $ref->writeSection('lang_model', $arr2);
                $ref->updateFile();
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function delete_sql()
    {
        if(empty(static::$path)) {
            throw new Exception('variable path is empty! in trait_install');
        }

        $model  = static::get_admin_model('default');
        $sql    = helper::getSql(
            static::$path.'admin'.DS.'resources'.DS.'uninstall.sql'
        );

        foreach($sql as $el) {
            $model->execute($el);
        }
    }

    /**
     * @throws Exception
     */
    public static function delete_files()
    {
        $name = get_called_class();

        if(empty(static::$path)) {
            throw new Exception('variable path is empty! in trait_install');
        }

        helper::remDir(SITE_PATH.'lang'.DS.$name);
        helper::remDir(SITE_PATH.'www'.DS.'js'.DS.$name);
        helper::remDir(SITE_PATH.'www'.DS.'css'.DS.$name);
        helper::remDir(SITE_PATH.'www'.DS.'media'.DS.$name);
        helper::remDir(substr(static::$path, 0, strlen(static::$path)-1));
    }

    /**
     *
     */
    public static function delete_layouts()
    {
        $name = get_called_class();

        if(($menu = system::get_menu()) != -1) {
            $menu->delete_extension_layouts($name);
        }
    }
}