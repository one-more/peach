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
        $params = \menu::read_lang('layouts_page');

        $model = \menu::get_admin_model('layouts');

        $arr = $model->get_layouts();

        $trs = '';

        foreach($arr as $el) {
            $tr = '';

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['id']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['alias']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['extension']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['position']
                ]
            );
            $tr .= $td;

            $i1 = \dom::create_element(
                'i',
                [
                    'class'         => 'icon-edit float-left
                        cursor-pointer edit-layout-icon',
                    'data-params'   => $el['id']
                ]
            );

            $i2 = \dom::create_element(
                'i',
                [
                    'class'         => 'icon-trash margin-left-2 cursor-pointer
                    delete-layout-icon',
                    'data-params'   => $el['id']
                ]
            );
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $i1.$i2
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
            \menu::$path.'admin'.DS.'views'.DS.'layouts'
        );
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
            $hidden = ['extension', 'class_name', 'controller', 'position'];
            $lang = \menu::read_lang('layouts_page');

            $error_msg = false;

            foreach($hidden as $el) {
                if(empty($_POST[$el])) {
                    $error_msg = 'some of fields [extension, class_name, controller,
                     position] are
                        empty';
                }
            }

            if(!$error_msg && empty($_POST['url'])) {
                $error_msg = $lang['select_url'];
            }


            if(!$error_msg) {

                $model = \menu::get_admin_model('layouts');

                $errors = $model->create($_POST);

                if(is_array($errors)) {

                    return json_encode(['error' => $errors]);
                }
                elseif(!empty($errors)) {
                    \comet::add_message([
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'showNoty',
                        'params'    => [$errors, 'error']
                    ]);
                }
                else {
                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'App',
                            'method'    => 'showNoty',
                            'params'    => [$lang['layout_created'], 'success']
                        ]
                    );

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'App',
                            'method'    => 'closeModal',
                            'params'    => []
                        ]
                    );
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

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        if($id) {

            $model = \menu::get_admin_model('layouts');

            $model->delete($id);
        }
        else {
            throw new \Exception('empty layout id');
        }
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function edit($id)
    {
        if($_POST) {
            $model = \menu::get_admin_model('layouts');

            $lang = \menu::read_lang('layouts_page');

            if(empty($_POST['url'])) {
                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'showNoty',
                        'params'    => [$lang['select_url'], 'error']
                    ]
                );
            }
            else {
                $errors = $model->update($_POST, $id);

                if($errors) {

                    return ['error' => $errors];
                }
                else {

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'App',
                            'method'    => 'closeModal',
                            'params'    => []
                        ]
                    );

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'MenuView',
                            'method'    => 'update_layouts_table',
                            'params'    => []
                        ]
                    );

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'App',
                            'method'    => 'showNoty',
                            'params'    => [$lang['layout_updated'], 'success']
                        ]
                    );
                }
            }
        }
        else {
            $params = \menu::read_lang('layouts_page');

            $template = \site::get_template();

            $arr = $template::get_positions();

            $model = \menu::get_admin_model('layouts');
            $layout = $model->get($id);

            $opts = '';

            foreach($arr as $el) {
                if($layout['position'] == $el) {
                    $opt = \dom::create_element(
                        'option',
                        [
                            'value'     => $el,
                            'text'      => $el,
                            'selected'  => ''
                        ]
                    );
                }
                else {
                    $opt = \dom::create_element(
                        'option',
                        [
                            'value'     => $el,
                            'text'      => $el
                        ]
                    );
                }

                $opts .= $opt;
            }

            $params['positions'] = $opts;

            $arr = \menu::get_urls();
            $urls = '';
            $layout_urls = [];
            foreach($layout['urls'] as $el) {
                $layout_urls[] = $el['url'];
            }

            foreach($arr as $el) {
                if(in_array($el['url'], $layout_urls)) {
                    $span = \dom::create_element(
                        'span',
                        [
                            'class' => 'label label-info float-left margin-2px
                                cursor-pointer',
                            'text'  => $el['url']
                        ]
                    );
                    $chbx = \dom::create_element(
                        'input',
                        [
                            'type'      => 'checkbox',
                            'name'      => 'url[]',
                            'checked'   => '',
                            'class'     => 'hide',
                            'value'     => $el['url']
                        ]
                    );
                    $span = $span.$chbx;
                }
                else {
                    $span = \dom::create_element(
                        'span',
                        [
                            'class' => 'label float-left margin-2px
                                cursor-pointer',
                            'text'  => $el['url']
                        ]
                    );
                }
                $urls .= $span;
            }

            $params['urls'] = $urls;
            $params['id'] = $id;
            $params['alias'] = $layout['alias'];

            return \templator::getTemplate(
                'edit',
                $params,
                \menu::$path.'admin'.DS.'views'.DS.'layouts'
            );
        }
    }

    /**
     * @param $url
     * @return mixed
     */
    public function get_page($url)
    {
        $model = \menu::get_admin_model('layouts');

        return $model->get_page($url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_layout_params($id)
    {
        $model = \menu::get_admin_model('layouts');

        return $model->get_layout_params($id);
    }
}