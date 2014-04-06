<?php

namespace articles_admin;

/**
 * Class viewscontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class viewscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        $iterator = new \FilesystemIterator(
            \articles::$path.'admin'.DS.'controllers');
        $views = [];

        $params = \articles::read_lang('views_page');

        foreach($iterator as $el) {
            if(
                ($view =
                    \articles::get_admin_controller($el->getBaseName('.php'))
                ) instanceof \view_controller_interface) {
                $views[] = $view->get_info();
            }
        }

        $trs = '';

        foreach($views as $k=>$el) {
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
                    'class' => 'articles-create-layout-btn cursor-pointer',
                    'text'  => $el['alias'],
                    'data-params'   => $el['name']
                ]
            );
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $a
                ]
            );
            $tr .= $td;

            $desc = strlen($el['description']) < 100 ? $el['description'] :
                substr($el['description'], 97).'...';
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
            \articles::$path.'admin'.DS.'views'.DS.'views'
        );
    }
}