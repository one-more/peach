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
        $this->extension = 'installer';
    }

    /**
     * @return mixed
     */
    public function get_extensions()
    {
        $model = installer::getAdminModel('default');

        return $model->get_all('extensions');
    }
}