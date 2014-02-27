<?php

namespace system_admin;

/**
 * Class langcontroller
 * @package system_admin
 * @author Nikolaev D.
 */
class langcontroller extends \supercontroller {
    use \trait_extension_controller;

    /**
     * @var
     */
    public  $extension;

    /**
     * @var
     */
    public $_cache_path;

    public function __construct()
    {
        $this->extension = 'system';

        $this->_cache_path = \system::$path.'admin'.DS.'cache'.DS;
    }

    /**
     * @return string|mixed
     */
    public function display()
    {
        $params = \system::read_lang('lang-page');

        $options = '';

        $arr = $this->get_list();

        $cur_lang = \system::get_current_lang(true);

        foreach($arr as $el) {

            $attrs = ['value'=>$el['key'], 'text'=>$el['alias']];

            if($el['key'] == $cur_lang) {
                $attrs['selected'] = '';
            }

            $options .= \dom::create_element('option', $attrs);
        }

        $params['options'] = $options;

        return \templator::getTemplate(
            'index',
            $params,
            \system::$path.'admin'.DS.'views'.DS.'lang'
        );
    }

    /**
     * @return mixed
     */
    public function get_list()
    {
        if($cache = $this->get_cache_view('langs')) {
            return json_decode($cache, true);
        }
        else {
            $model = \system::get_admin_model('lang');

            $cache = $model->get_all('languages');

            $this->set_cache_view('langs', json_encode($cache));

            return $cache;
        }
    }

    /**
     * @param $key
     * @return array
     */
    public function change($key)
    {
        if($key) {
            $ini = \factory::getIniServer();

            $ini->write('language', 'current', $key);

            $ini->updateFile();
        }
        else {
            return ['error'=>'empty key'];
        }
    }

    /**
     * @return array
     */
    public function add()
    {
        if($_POST) {
            $model = \system::get_admin_model('lang');

            $error = $model->add($_POST['alias'], $_POST['key']);

            if(empty($error['error'])) {
                $this->delete_cache_view('langs');

                $error['added_lang'] = ['alias'=>$_POST['alias'], 'key'=>$_POST['key']];
            }

            return $error;
        }
        else {
            return ['error'=>'no data'];
        }
    }
}