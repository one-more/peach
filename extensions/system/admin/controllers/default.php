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
        $arr = \admin::get_menu_extensions();

        $opts = \system::read_params('options');

        $menu_changed = false;

        if(count($arr) > 0 && $opts['menu'] == -1) {
            $menu = $arr[0]['name'];

            \system::write_params('options', 'menu', $menu);

            $menu_changed = true;
        }

        if(count($arr) == 0 && $opts['menu'] != -1) {
            \system::write_params('options', 'menu', -1);

            $menu_changed = true;
        }

        if($menu_changed) {
            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'SystemModel',
                    'method'    => 'initialize',
                    'params'    => []
                ],
                'me_site'
            );

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'SystemModel',
                    'method'    => 'initialize',
                    'params'    => []
                ],
                'site_users'
            );
        }

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