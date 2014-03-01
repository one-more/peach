<?php
/**
* Class menu
*
* @author Nikolaev D.
*/
class menu implements menu_extension_interface {
	use trait_extension;

	public static function create_layout()
	{
		
	}

	public static function get_create_layout_html()
	{
		
	}

	public static function get_layout_params($id)
	{
		
	}

	public static function get_page($id)
	{
		
	}

	public static function get_version()
	{
		return '1.0';
	}

	public static function get_info()
	{
		return static::read_lang('info');	
	}

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

	public function install()
	{
		$model = static::get_admin_model('default');
		$sql = helper::getSql(SITE_PATH.'extensions'.DS.'menu'.DS.'admin'.DS.'resources'.DS.'install.sql');

		foreach($sql as $el) {
			$model->execute($el);
		}
	}
} 
