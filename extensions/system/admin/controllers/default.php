<?php
namespace system_admin;

/**
 * Class defaultcontroller
 * @package system_admin
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \system::read_lang('default-page');

        $params['js'] = [
            \dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.core.min.js']),
            \dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.widget.min.js']),
            \dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.tabs.min.js']),
            \dom::create_element('script', ['src'=>'/js/system/admin/views/default_view.js'])
        ];

        \builder::build('system_admin_default.js', $params['js']);

        $params['js'] = '/js/builder/system_admin_default.js';

        return \templator::getTemplate(
            'index',
            $params,
            \system::$path.'admin'.DS.'views'.DS.'default'
        );
    }

    /**
     * @return array|bool|string
     */
    public function get_options()
    {
        return \system::read_params('options');
    }

    /**
     * @param $arr
     * @return array
     */
    public function update_options($arr)
    {
        if(is_array($arr)) {
            \system::write_params('options', $arr);
        }
        else {
            return ['error' => 'params is not array'];
        }
    }
}