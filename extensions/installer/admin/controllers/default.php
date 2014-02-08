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

    /**
     * @var
     */
    private $_cache_path;

    public function __construct()
    {
        $this->extension = 'installer';

        $this->_cache_path = '..'.DS.'extensions'.DS.'installer'.DS.'admin'.DS.'cache'.DS;
    }

    /**
     * @return mixed
     */
    public function get_extensions()
    {
        if($cache = $this->get_cache_view('extensions')) {
            return $cache;
        }
        else {
            $model = installer::getAdminModel('default');

            $cache = $model->get_all('extensions');

            if(is_array($cache)) {
                $this->set_cache_view('extensions', json_encode($cache));
            }

            return $cache;
        }
    }

    /**
     * @return bool|string
     */
    public function get_templates()
    {
        if($cache = $this->get_cache_view('templates')) {
            return $cache;
        }
        else {
            $model = installer::getAdminModel('default');

            $cache = $model->get_all('templates');

            if(is_array($cache)) {
                $this->set_cache_view('templates', json_encode($cache));
            }

            return $cache;
        }
    }

    /**
     * @return bool|string
     */
    public function get_editors()
    {
        if($cache = $this->get_cache_view('editors')) {
            return $cache;
        }
        else {
            $model = installer::getAdminModel('default');

            $cache = $model->get_all('editors');

            if(is_array($cache)) {
                $this->set_cache_view('editors', json_encode($cache));
            }

            return $cache;
        }
    }
}