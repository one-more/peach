<?php
namespace menu_admin;

/**
 * Class viewscontroller
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class viewscontroller extends  \supercontroller {

    /**
     *
     */
    public function display()
    {
        $iterator = new \FilesystemIterator(\menu::$path.'admin'.DS.'controllers');
        $views = [];
        $params = \menu::read_lang('views_page');

        foreach($iterator as $el) {

            if( ($view = \menu::get_admin_controller($el->getBaseName('.php')))
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

            $a = \dom::create_element(
                'a',
                [
                    'class'         => 'menu-create-layout-btn cursor-pointer',
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

            $desc = strlen($v['description']) < 100 ? $v['description'] :
                substr($v['description'].'...', 0, 97);
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $desc
                ]
            );
            $tr .= $td;

            $trs .= \dom::create_element(
                'tr',
                [
                    'text'  => $tr
                ]
            );
        }

        $params['trs'] = $trs;

        return \templator::getTemplate(
            'index',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'views'
        );
    }
}