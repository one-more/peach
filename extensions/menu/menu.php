<?php
/**
* Class menu
*
* @author Nikolaev D.
*/
class menu implements menu_extension_interface {
	use trait_extension;

    /**
     * @return mixed|void
     */
    public static function create_layout()
	{
		$controller = static::get_admin_controller('layouts');

        echo $controller->create_layout();
	}

    /**
     * @return mixed
     */
    public static function get_create_layout_html()
	{
		$controller = static::get_admin_controller('layouts');

        return $controller->get_create_layout_html();
	}

    /**
     * @param $id
     * @return mixed|void
     */
    public static function get_layout_params($id)
	{
		$controller = static::get_admin_controller('layouts');

        return $controller->get_layout_params($id);
	}

    /**
     * @param $link
     * @return mixed|void
     */
    public static function get_page($link)
	{
		$controller = static::get_admin_controller('layouts');

        $arr = $controller->exec('get_page', $link);

        echo is_array($arr) ? json_encode($arr) : $arr;
	}

    /**
     * @return mixed
     */
    public static function get_urls()
    {
        $controller = static::get_admin_controller('links');

        return $controller->get_urls();
    }

    /**
     * @return mixed|string
     */
    public static function get_version()
	{
		return '1.0';
	}

    /**
     * @return array
     */
    public static function get_info()
	{
		return static::read_lang('info');	
	}

    /**
     * @return mixed|void
     */
    public static function delete()
	{
		if(core::$mode == 'admin' && user::get_token()) {
            $model = static::get_admin_model('default');
            $sql = helper::getSql(SITE_PATH.'extensions'.DS.'menu'.DS.'admin'.DS.'resources'.DS.'uninstall.sql');

            foreach($sql as $el) {
                $model->execute($el);
            }

            helper::remDir(SITE_PATH.'lang'.DS.'menu');
            helper::remDir(SITE_PATH.'www'.DS.'js'.DS.'menu');
            helper::remDir(SITE_PATH.'www'.DS.'css'.DS.'menu');
            helper::remDir(SITE_PATH.'media'.DS.'menu');
            helper::remDir(SITE_PATH.'extensions'.DS.'menu');
        }
	}

    /**
     *
     */
    public function install()
	{
		$model = static::get_admin_model('default');
		$sql = helper::getSql(SITE_PATH.'extensions'.DS.'menu'.DS.'admin'.DS.'resources'.DS.'install.sql');

		foreach($sql as $el) {
			$model->execute($el);
		}

		$ini 	= factory::getIniServer	(
				SITE_PATH.'extensions'.DS.'menu'.DS.'admin'.DS.'resources'.DS.'lang_model.ini'
				);
		$ref 	= factory::getIniServer(SITE_PATH.'lang'.DS.'references'.DS.'en-EN.ini');
		$arr1 	= $ini->readSection('en-EN');
		$arr2	= $ref->readSection('lang_model');
		$arr2	= array_merge($arr2, $arr1);
		$ref->writeSection('lang_model', $arr2);
		$ref->updateFile();

		$arr1	= $ini->readSection('ru-RU');
		$ref	= factory::getIniServer(SITE_PATH.'lang'.DS.'references'.DS.'ru-RU.ini');
		$arr2	= $ref->readSection('lang_model');
		$arr2	= array_merge($arr2, $arr1);
		$ref->writeSection('lang_model', $arr2);
		$ref->updateFile();
	}

    /**
     * @param $class
     * @return mixed|void
     */
    public function delete_extension_layouts($class)
    {
        $controller = static::get_admin_controller('layouts');

        $controller->delete_extension_layouts($class);
    }
} 
