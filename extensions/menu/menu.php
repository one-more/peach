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

        return $controller->create_layout();
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
		
	}

    /**
     * @param $id
     * @return mixed|void
     */
    public static function get_page($id)
	{
		echo json_encode([]);
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
		$model = static::get_admin_model('default');
		$sql = helper::getSql(SITE_PATH.'extensions'.DS.'menu'.DS.'admin'.DS.'resources'.DS.'uninstall.sql');

		foreach($sql as $el) {
			$model->execute($el);
		}

		helper::remDir(SITE_PATH.'lang'.DS.'menu');
		helper::remDir(SITE_PATH.'js'.DS.'menu');
		helper::remDir(SITE_PATH.'css'.DS.'menu');
		helper::remDir(SITE_PATH.'media'.DS.'menu');
		helper::remDir(SITE_PATH.'extensions'.DS.'menu');
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
	}
} 
