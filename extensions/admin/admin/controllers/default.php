<?php
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends supercontroller {
    use trait_extension_controller;

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
        $template = admin::read_params('options')['template'];

        $template::start();
    }

    /**
     * @return array|bool|string
     */
    public function get_options()
    {
        return admin::read_params('options');
    }
}