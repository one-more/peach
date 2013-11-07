<?php
/**
 * Class sitecontroller
 *
 * @author - Dmitriy Nikolaev
 */
class sitecontroller extends supercontroller{

	use trait_extension_controller;
    use trait_validator;

	/**
	 * @var string - need for getLAng function
	 */
	public $extension;

	/**
	 * @var string - need for cache functions
	 */
	public $cache_path;

	/**
	 * @var array
	 */
	public $js = [
		'<script src="/js/installer/admin/view/install_site_view.js" ></script>'
	];

	/**
	 * @var array
	 */
	public $css = [
		'<link rel="stylesheet" href="/css/installer/admin/install_site.css" />'
	];

	public function __construct() {
		$cache_path = installer::$path.'admin/cache/';

		$extension = 'installer';
	}

    /**
     * displays entry point of install site
     */
    public function display() {
        $css = document::$css_files;

		$css = array_merge($css, $this->css);

        $js = document::$js_files;

		$js = array_merge($js, $this->js);

		$params = ['css'=>$css, 'js'=>$js];

		$default = [
			'lang' => 'en-EN'
		];

		$default = array_merge($default, $_REQUEST);

		$ini = factory::getIniServer('../lang/installer/admin/'.$default['lang'].'.ini');

		$lang = $ini->readSection('install_site');

		$params = array_merge($params, $lang);

		$params['all'] = templator::getTemplate('install', $params, installer::$path.'admin/views/site');

        $html = templator::getTemplate('index', $params , installer::$path.'admin/views/site');

        if(empty($_REQUEST['lang'])) {
			return $html;
		}
		else {
			return $params['all'];
		}
    }

    public static function complete()
    {
        if($_POST) {
            $error = static::check($_POST, [
                'dbname'        => 'not_empty',
                'dbuser'        => 'not_empty',
                'dbpass'        => 'not_empty',
                'adminlogin'    => 'not_empty',
                'adminpassword' => ['not_empty', 'password'],
                'adminemail'    => ['not_empty', 'email']
            ]);

            return ['error' => $error];
        }
        else {
            return ['error' => 'no data'];
        }
    }
}