<?php
namespace admin_admin;
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {
    use \trait_extension_controller;

    /**
     * @var
     */
    public $extension;

    public function __construct()
    {
        $this->extension = 'admin';
    }

    /**
     * display template
     */
    public function display()
    {
        if(!\user::is_auth() || !\user::is_admin()) {
            \user::auth('/admin');
        }
        else {
            $template = \admin::read_params('options')['template'];

            $template::start();
        }
    }

    /**
     * @return array|bool|string
     */
    public function get_options()
    {
        return \admin::read_params('options');
    }
}