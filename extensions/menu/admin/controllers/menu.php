<?php

namespace menu_admin;

/**
 * Class menucontroller
 * @package menu_admin
 */
class menucontroller extends \supercontroller implements \view_controller_interface {

    /**
     * @return mixed|void
     */
    public function get_info()
    {
        /**
         *
         */
        return \menu::read_lang('menu_view');
    }

    /**
     * @return string
     */
    public function display()
    {
        $params = \menu::read_lang('menu_view');

        $model = \menu::get_admin_model('menus');

        $arr = $model->get_menus();
        $opts = '';

        foreach($arr as $el) {
            $opt = \dom::create_element(
                'option',
                [
                    'text'  => $el['alias'],
                    'value' => $el['id']
                ]
            );

            $opts .= $opt;
        }

        $params['opts'] = $opts;

        if(count($arr)) {
            $params['create_layout_html'] = \menu::get_create_layout_html();
        }
        else {
            $params['create_layout_html'] =
                \templator::get_warning($params['no_menus']);
        }

        $params['class']        = 'menu';
        $params['extension']    = \menu::get_info()['alias'];
        $params['controller']   = 'menu';

        return \templator::getTemplate(
            'index',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'menu'
        );
    }
}