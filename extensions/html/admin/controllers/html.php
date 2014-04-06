<?php

namespace html_admin;

/**
 * Class htmlcontroller
 *
 * @package html_admin
 *
 * @author Nikolaev D.
 */
class htmlcontroller extends \supercontroller implements \view_controller_interface {

    public function display()
    {
        $params = \html::read_lang('html_view');

        $menu = \system::get_menu();

        if($menu == -1) {
            $msg = \factory::get_reference('errors')['no_menu'];

            return \templator::get_warning($msg);
        }
        else {
            $model  = \html::get_admin_model('records');

            $arr    = $model->get_records();

            $opts   = '';

            if(count($arr) == 0) {
                return \templator::get_warning($params['no_records']);
            }

            foreach($arr as $el) {
                $opt = \dom::create_element(
                    'option',
                    [
                        'value' => $el['id'],
                        'text'  => $el['alias']
                    ]
                );

                $opts   .= $opt;
            }

            $params['opts']                 = $opts;
            $params['create_layout_html']   = $menu::get_create_layout_html();
            $params['action']               =
                "index.php?class={$menu}&method=create_layout";
            $params['class']                = 'html';
            $params['extension']            = \html::get_info()['alias'];
            $params['controller']           = 'html';

            return \templator::getTemplate(
                'index',
                $params,
                \html::$path.'admin'.DS.'views'.DS.'html'
            );
        }
    }

    /**
     * @return array
     */
    public function get_info()
    {
        return \html::read_lang('html_view');
    }
}