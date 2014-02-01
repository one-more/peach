<?php
namespace user_admin;

/**
 * Class viewscontroller
 *
 * @package user_admin
 *
 * @author Nikolaev D.
 */
class viewscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        $iterator = new \FilesystemIterator(\user::$path.'admin'.DS.'controllers');

        $views = [];

        $params = \user::read_lang('views_page');

        foreach($iterator as $el) {
            if(\user::get_admin_controller($el->getBasename('.php')) instanceof \view_controller_interface) {
                $view = \user::get_admin_controller($el->getBasename('.php'));

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
                    'class'         => 'user-create-layout-btn cursor-pointer',
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

            $td = \dom::create_element(
                'td',
                [
                    'text'  => strlen($v['description']) < 100 ? $v['description'] : substr($v['description'], 0, 100)
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
            \user::$path.'admin'.DS.'views'.DS.'views'
        );
    }
}