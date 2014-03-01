<?php

namespace system_admin;

/**
 * Class menucontroller
 * @package system_admin
 * @author Nikolaev D.
 */
class menucontroller extends \supercontroller {
    /**
     * @return string
     */
    public function display()
    {
        $params = \system::read_lang('menu-page');

        $arr = \admin::get_menu_extensions();

        if(count($arr) > 0) {

            $options = '';

            $menu = \system::get_menu();

            foreach($arr as $el) {
                $alias = $el['name']::get_info()['alias'];

                $attrs =  [
                    'value' => $alias,
                    'text'  => $el['name']
                ];

                if($el == $menu) {
                    $attrs['selected'] = '';
                }

                $options .= \dom::create_element('<option>',$attrs);
            }

            $params['select'] = \dom::create_element('select', [
                'text'  =>$options,
                'class' => 'menu-select',
                'name'  => 'menu'
            ]);
        }
        else {
            $params['select'] = $params['no_menu'];
        }

        return \templator::getTemplate(
            'index',
            $params,
            \system::$path.'admin'.DS.'views'.DS.'menu'
        );
    }
}