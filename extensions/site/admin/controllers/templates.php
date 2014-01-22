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

            $td .= \dom::create_element('td', ['text'=>$info['alias']]);

            $td .= \dom::create_element('td', ['text'=> $info['author']]);

            $img = \dom::create_element('img', [
                'src'       =>'/media/images/preview.png',
                'data-full' => $info['preview']
            ]);

            $td .= \dom::create_element('td', ['text'=>$img]);

            $src = $el['name'] == $template ? '/media/images/ok.png' : '/media/images/bullet.png';

            $img = \dom::create_element('img', [
                'src'   => $src,
                'class' => 'choose_template'
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
}