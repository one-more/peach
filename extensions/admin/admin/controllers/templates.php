<?php
namespace admin_admin;

/**
 * Class templatescontroller
 *
 * @package admin_admin
 *
 * @author Nikolaev D
 */
class templatescontroller extends \supercontroller {

    public function display()
    {
        $params = \admin::read_lang('templates_page');

        $template = \admin::read_params('options')['template'];

        $arr = \admin::get_admin_templates();

        $trs = '';

        foreach($arr as $el) {
            $tr = '';
            $info = $el['name']::get_info();

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['id']
                ]
            );
            $tr .= $td;

            $a = \dom::create_element(
                'a',
                [
                    'href'  => "/admin/$el[name]/options",
                    'text'  => $info['alias']
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
                    'text'  => $info['author']
                ]
            );
            $tr .= $td;

            $img = \dom::create_element(
                'img',
                [
                    'class'         => $info['preview']? 'cursor-pointer modal-slider' : '',
                    'src'           => DS.'media'.DS.'images'.DS.'preview.png',
                    'data-params'   => $info['preview']? $info['preview'] : ''
                ]
            );
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $img
                ]
            );
            $tr .= $td;

            $img = \dom::create_element(
                'img',
                [
                    'src'           => $el['name'] == $template ? DS.'media'.DS.'images'.DS.'ok.png':
                            DS.'media'.DS.'images'.DS.'bullet.png',
                    'class'         => 'admin-choose-template cursor-pointer '.
                        ($el['name'] == $template ? 'selected' : ''),
                    'data-params'   => $el['name']
                ]
            );
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $img
                ]
            );
            $tr .= $td;

            $trs .= $tr;
        }

        $params['trs'] = $trs;

        return \templator::getTemplate(
            'index',
            $params,
            \admin::$path.'admin'.DS.'views'.DS.'templates'
        );
    }

    /**
     * @param $name
     */
    public function select_template($name)
    {
        if(strval($name)) {
            \admin::write_params('options', 'template', $name);

            \comet::add_message([
                'task'      => 'delegate',
                'object'    => 'App',
                'method'    => 'loadPage',
                'params'    => ['/admin']
            ]);
        }
        else {
            $ref = \factory::get_reference('errors')['invalid_param'];

            \comet::add_message([
                'task'      => 'delegate',
                'object'    => 'App',
                'method'    => 'showNoty',
                'params'    => [$ref, 'success']
            ]);
        }
    }
}