<?php

namespace menu_site;

/**
 * Class menucontroller
 *
 * @package menu_site
 *
 * @author Nikoalev D.
 */
class menucontroller extends \supercontroller {

    /**
     * @param $arr
     * @return string
     */
    public function display($arr)
    {
        $params = \menu::get_layout_params($arr['id']);


        $model = \menu::get_site_model('menu');

        $list = $model->get_menu($params['menu']);

        if(empty($params['css_class'])) {
            if($params['layout'] == 'horizon') {
                $lis = $this->get_default_structure($list);

                $ul = \dom::create_element(
                    'ul',
                    [
                        'class' => 'nav nav-pills',
                        'text'  => $lis
                    ]
                );
            }
            else {
                $lis = $this->get_horizontal_structure($list);

                $ul = \dom::create_element(
                    'ul',
                    [
                        'class' => 'nav nav-list',
                        'text'  => $lis
                    ]
                );
                $ul = \dom::create_element(
                    'div',
                    [
                        'class' => 'inline-block',
                        'text'  => $ul
                    ]
                );
            }
        }
        else {
            $lis = $this->get_custom_structure($list);
            $ul = \dom::create_element(
                'ul',
                [
                    'class' => $params['css_class'],
                    'text'  => $lis
                ]
            );
        }

        return $ul;
    }

    /**
     * @param $arr
     * @return string
     */
    public function get_default_structure($arr)
    {
        $lis = '';

        foreach($arr as $el) {
            if(empty($el['sub_menu'])) {
                $a = \dom::create_element(
                    'a',
                    [
                        'href'  => $el['url'],
                        'text'  => $el['alias']
                    ]
                );
                $li = \dom::create_element(
                    'li',
                    [
                        'text'  => $a
                    ]
                );
            }
            else {
                $sub = $this->get_default_structure($el['sub_menu']);
                $ul = \dom::create_element(
                    'ul',
                    [
                        'class' => 'dropdown-menu',
                        'text'  => $sub
                    ]
                );
                $b = \dom::create_element(
                    'b',
                    [
                        'class' => 'caret'
                    ]
                );
                $a = \dom::create_element(
                    'a',
                    [
                        'class'         => 'dropdown-toggle external',
                        'data-toggle'   => 'dropdown',
                        'href'          => $el['url'],
                        'text'          => $el['alias'].$b
                    ]
                );
                $li = \dom::create_element(
                    'li',
                    [
                        'class' => 'dropdown',
                        'text'  => $a.$ul
                    ]
                );
            }

            $lis .= $li;
        }

        return $lis;
    }

    /**
     * @param $arr
     * @return string
     */
    public function get_custom_structure($arr)
    {
        $lis = '';

        foreach($arr as $el) {
            if(empty($el['sub_menu'])) {
                $a = \dom::create_element(
                    'a',
                    [
                        'href'  => $el['url'],
                        'text'  => $el['alias']
                    ]
                );
                $li = \dom::create_element(
                    'li',
                    [
                        'text'  => $a
                    ]
                );
            }
            else {
                $sub    = $this->get_custom_structure($el['sub_menu']);
                $ul     = \dom::create_element(
                    'ul',
                    [
                        'text'  => $sub
                    ]
                );
                $a      = \dom::create_element(
                    'a',
                    [
                        'href'  => $el['url'],
                        'text'  => $el['alias']
                    ]
                );
                $li = \dom::create_element(
                    'li',
                    [
                        'text'  => $a.$ul
                    ]
                );
            }

            $lis .= $li;
        }

        return $lis;
    }

    /**
     * @param $arr
     * @return string
     */
    public function get_horizontal_structure($arr) {
        $lis = '';

        foreach($arr as $el) {
            if(empty($el['sub_menu'])) {
                $a = \dom::create_element(
                    'a',
                    [
                        'href'  => $el['url'],
                        'text'  => $el['alias']
                    ]
                );
                $li = \dom::create_element(
                    'li',
                    [
                        'text'  => $a
                    ]
                );
            }
            else {
                $sub = $this->get_horizontal_structure($el['sub_menu']);

                $ul = \dom::create_element(
                    'ul',
                    [
                        'class' => 'nav nav-list',
                        'text'  => $sub
                    ]
                );
                $a = \dom::create_element(
                    'a',
                    [
                        'href'  => $el['url'],
                        'text'  => $el['alias']
                    ]
                );
                $li = \dom::create_element(
                    'li',
                    [
                        'text'  => $a.$ul
                    ]
                );
            }

            $lis .= $li;
        }

        return $lis;
    }
}