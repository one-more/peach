<?php

namespace html_admin;

/**
 * Class viewscontroller
 *
 * @package html_admin
 *
 * @author Nikolaev D.
 */
class viewscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        $iterator   = new \FilesystemIterator(\html::$path.'admin'.DS.'controllers');
        $params     = \html::read_lang('views_page');

        $views = [];

        foreach($iterator as $el) {

            if(($view = \html::get_admin_controller($el->getBaseName('.php')))
                instanceof \view_controller_interface) {
                $views[] = $view->get_info();
            }
        }

        $trs = '';

        foreach($views as $k=>$v) {
            $tr = '';

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $k+1
                ]
            );
            $tr .= $td;

            $a  = \dom::create_element(
                'a',
                [
                    'class'         => 'html-create-layout-btn cursor-pointer',
                    'text'          => $v['alias'],
                    'data-params'   => $v['name']
                ]
            );
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $a
                ]
            );
            $tr .= $td;

            $desc = strlen($v['description']) > 100 ?
                substr(0, 97, $v['description']).'...' :
                $v['description'];
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $desc
                ]
            );
            $tr .= $td;

            $trs    .= \dom::create_element(
                'tr',
                [
                    'text'  => $tr
                ]
            );
        }

        $params['trs']  =   $trs;

        return \templator::getTemplate(
            'index',
            $params,
            \html::$path.'admin'.DS.'views'.DS.'views'
        );
    }
}