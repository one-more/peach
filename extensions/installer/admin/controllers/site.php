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
		'<script src="/js/installer/admin/view/install_site_view.js" ></script>',
        '<script src="/js/installer/admin/module/router.js"></script>',
	];

	/**
	 * @var array
	 */
	public $css = [
		'<link rel="stylesheet" href="/css/installer/admin/install_site.css" />'
	];

	public function __construct() {
		$this->cache_path = installer::$path.'admin/cache/';

		$this->extension = 'installer';
	}

    /**
     * @var null array contains error messages
     */
    public static $reference = null;

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

        if(empty($_REQUEST['ajax'])) {
			return $html;
		}
		else {
			return $params['all'];
		}
    }

    public function complete()
    {
        if($_POST) {
            $ini = factory::getIniServer('../lang/installer/admin/'.$_POST['language'].'.ini');

            static::$reference = $ini->readSection('error_reference');

            $error = static::check($_POST, [
                'dbname'        => 'not_empty',
                'dbuser'        => 'not_empty',
                'dbpass'        => 'not_empty',
                'adminlogin'    => 'not_empty',
                'adminpassword' => ['not_empty', 'password'],
                'adminemail'    => ['not_empty', 'email']
            ]);

            if(!$error) {
                $ini = factory::getIniServer('../configuration.ini');

                $arr = [
                    'db_name'   => $_POST['dbname'],
                    'db_user'   => $_POST['dbuser'],
                    'db_pass'   => $_POST['dbpass'],
                ];

                $ini->writeSection('db_params', $arr);

                $ini->write('language', 'current', $_POST['language']);

                $ini->updateFile();

                ob_start();

                $model = installer::getAdminModel('site');

                $buffer = ob_get_clean();

                //if database options are wrong
                if($buffer) {
                    $error['dbname'] = static::$reference['wrong_data'];
                    $error['dbuser'] = static::$reference['wrong_data'];
                    $error['dbpass'] = static::$reference['wrong_data'];

                    //close ini handler
                    $ini = null;

                    //delete ini file
                    unlink('../configuration.ini');

                    return ['error' => $error];
                }

                $sql = helper::getSql(installer::$path.'admin/resources/siteinstall.sql');

                foreach($sql as $el) {
                    $model->execute($el);
                }

                $user = [
                    'login'         => $_POST['adminlogin'],
                    'password'      => $_POST['adminpassword'],
                    'credentials'   => 'SUPER_ADMIN'
                ];

                $info = [
                    'email' => $_POST['adminemail']
                ];

                user::create($user, $info);

                $ini = null;

                $ini = factory::getIniServer('../extensions/installer/installer.ini');

                $ini->write('site', 'installed', 'true');

                $ini->updateFile();
            }

            return ['error' => $error];
        }
        else {
            $params = [
                'css'   => array_merge(document::$css_files , [
                    '<link rel="stylesheet" href="/css/installer/admin/install_site.css" />'
                ]),
                'js'    => array_merge(document::$js_files , [
                    '<script src="/js/installer/admin/view/install_site_view.js" ></script>',
                    '<script src="/js/installer/admin/module/router.js"></script>'
                ])
            ];

            $lang = $this->getLang('done_page');

            if(is_array($lang))
            {
                $params = array_merge($params, $lang);
            }

            $params['all'] = templator::getTemplate('done', $params, installer::$path.'admin/views/site');
            $html = templator::getTemplate('index', $params, installer::$path.'admin/views/site');

            return $html;
        }
    }
}