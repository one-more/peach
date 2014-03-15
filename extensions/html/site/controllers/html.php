<?php

namespace html_site;

/**
 * Class htmlcontroller
 *
 * @package html_site
 *
 * @author Nikolaev D.
 */
class htmlcontroller extends \supercontroller {

    use \trait_extension_controller;

    /**
     *
     */
    public function __construct()
    {
        $this->_extension   = 'html';
        $this->_cache_path  = \html::$path.\core::$mode.DS.'cache'.DS;
    }

    /**
     * @param $arr
     * @return mixed
     */
    public function display($arr)
    {
        $menu = \system::get_menu();

        if($menu != -1) {
            $params = $menu::get_layout_params($arr['id']);

            $model  =   \html::get_admin_model('records');

            if($cache = $this->get_cache_view('record_'.$params['record'])) {
                $obj = json_decode($cache, true);
            }
            else {
                $obj    = $model->get($params['record']);

                $this->set_cache_view('record_'.$params['record'],
                    json_encode($obj));
            }

            return $obj['text'];
        }
    }
}