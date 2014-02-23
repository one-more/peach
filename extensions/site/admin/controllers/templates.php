<?php
namespace site_admin;

/**
 * Class templatescontroller
 * @package site_admin
 * @author Nikolaev D.
 */
class templatescontroller extends \supercontroller {
    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \site::read_lang('templates-page');

        $arr = \admin::get_site_templates();

        $trs = '';

        $template = \site::read_params('options')['template'];

        foreach($arr as $el) {
            $td = \dom::create_element('td', ['text'=> $el['id']]);

            $info = $el['name']::get_info();

            $a = \dom::create_element(
                'a',
                [
                    'text'  => $info['alias'],
                    'href'  => "/admin/$el[name]/options"
                ]
            );

            $td .= \dom::create_element('td', ['text'=>$a]);

            $td .= \dom::create_element('td', ['text'=> $info['author']]);

            if(!empty($info['preview'])) {
                $img = \dom::create_element(
                  'img',
                    [
                        'src'           => DS.'media'.DS.'images'.DS.'preview.png',
                        'class'         => 'modal-slider cursor-pointer',
                        'data-params'   => $info['preview']
                    ]
                );
            }
            else
            {
                $img = \dom::create_element('img', [
                    'src'       =>'/media/images/preview.png'
                ]);
            }

            $td .= \dom::create_element('td', ['text'=>$img]);

            $src = $el['name'] == $template ? '/media/images/ok.png' : '/media/images/bullet.png';

            $img = \dom::create_element('img', [
                'src'           => $src,
                'class'         => 'site_choose_template cursor-pointer '.($el['name'] == $template ?
                    'selected' : ''),
                'data-params'   => $el['name']
            ]);

            $td .= \dom::create_element('td', ['text'=>$img]);

            $trs .= \dom::create_element('tr', ['text'=>$td]);
        }

        $params['trs']  = $trs;

        return \templator::getTemplate(
            'index',
            $params,
            \site::$path.'admin'.DS.'views'.DS.'templates'
        );
    }

    /**
     * @param $name
     */
    public function select($name) {
        if($name) {
            \site::write_params('options', 'template', $name);

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'loadPage',
                    'params'    => ['/']
                ],
                'site_users'
            );

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'loadPage',
                    'params'    => ['/']
                ],
                'me_site'
            );
        }
    }
}