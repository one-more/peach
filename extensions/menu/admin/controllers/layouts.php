<?php
namespace menu_admin;

/**
 * Class layoutscontroller
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class layoutscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        return 'layouts';
    }

    /**
     * @return string
     */
    public function get_create_layout_html()
    {
        $params = \menu::read_lang('create_layout_page');

        $template = \site::get_template();

        $arr = $template::get_positions();

        $opts = '';

        foreach($arr as $el) {
            $opt = \dom::create_element(
                'option',
                [
                    'value'     => $el,
                    'text'      => $el
                ]
            );

            $opts .= $opt;
        }

        $params['positions'] = $opts;

        $arr = \menu::get_urls();

        if(count($arr)) {
            $urls = '';

            foreach($arr as $el) {
                $span = \dom::create_element(
                    'span',
                    [
                        'class'     => 'label float-left cursor-pointer margin-1',
                        'text'      => $el['url']
                    ]
                );

                $urls .= $span;
            }

            $params['urls'] = $urls;
        }
        else {
            $params['urls'] = $params['NO_URLS'];
        }

        return \templator::getTemplate(
            'create_layout',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'layouts'
        );
    }

    public function create_layout()
    {
        if($_POST) {
            $hidden = ['extension', 'class_name', 'controller'];
            $lang = \menu::read_lang('layouts_page');

            $error_msg = false;

            foreach($hidden as $el) {
                if(empty($_POST[$el])) {
                    $error_msg = 'some of fields [extension, class_name, controller] are
                        empty';
                }
            }

            \error::log($error_msg);

            if(!$error_msg && empty($_POST['url'])) {
                $error_msg = $lang['select_url'];
            }

            $default = [
                'name'          => '',
                'alias'         => '',
                'extension'     => '',
                'class'         => '',
                'controller'    => '',
                'position'      => '',
                'url'           => ''
            ];

            if(!$error_msg) {
                $data = array_merge($default, $_POST);

                $model = menu::get_admin_model('layouts');

                $error_msg = $model->create_layout($data);

                if(is_array($error_msg)) {
                    return json_encode(['error' => $error_msg]);
                }
            }
            else {
                \comet::add_message([
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => [$error_msg, 'error']
                ]);
            }
        }
        else {
            comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => ['post array is empty', 'error']
                ]
            );
        }
    }
}