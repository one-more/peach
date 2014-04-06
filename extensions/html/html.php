<?php

/**
 * Class html
 *
 * allows to display html code as layout
 *
 * @author Nikolaev D.
 */
class html implements user_extension_interface {
	
	use trait_extension, trait_install;

    static  $clear_cache = 1;

    /**
     * @return mixed|string
     */
    public static function get_version()
	{
		return '1.0';
	}

    /**
     * @return array|mixed
     */
    public static function get_info()
	{
		return static::read_lang('info');
	}

    /**
     *
     */
    public function install()
	{
		static::$path = SITE_PATH.'extensions'.DS.'html'.DS;

		static::install_sql();

		static::install_reference(['ru-RU', 'en-EN']);			
	}

    /**
     * @return mixed|void
     */
    public static function delete()
	{
        if(core::$mode == 'admin' && user::get_token()) {
            static::$path = SITE_PATH.'extensions'.DS.'html'.DS;

            static::delete_sql();

            static::delete_files();

            static::delete_layouts();
        }
	}	
} 
