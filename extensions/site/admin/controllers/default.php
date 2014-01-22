<?php
namespace site_admin;

/**
 * Class defaultcontroller
 * @package site_admin
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {
    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \site::read_lang('default-page');

        $params['js'] = [
            \dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.core.min.js']),
            \dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.widget.min.js']),
            \dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.tabs.min.js']),
            \dom::create_element('script', ['src'=>'/js/site/admin/views/site_view.js'])
        ];

        \builder::build('site_admin_default.js', $params['js']);

        $params['js'] = '/js/builder/site_admin_default.js';

        return \templator::getTemplate(
            'index',
            $params,
            \site::$path.'admin'.DS.'views'.DS.'default'
        );
    }
}