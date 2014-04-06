<?php
namespace menu_admin;

/**
 * Class itemscontroller
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class itemscontroller extends \supercontroller {

    /**
     * @param null $id
     * @return string
     */
    public function display($id = null)
    {
        $model = \menu::get_admin_model('items');

        $arr = $model->get_items($id);

        $trs = '';

        $params = \menu::read_lang('items_page');

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
                    'text'  => $el['url']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text'  => empty($el['menu_alias']) ? '' : $el['menu_alias']
                ]
            );
            $tr .= $td;

            $i1 = \dom::create_element(
                'i',
                [
                    'class'         => 'icon-edit cursor-pointer edit-menu-item-icon',
                    'data-params'   => $el['id']
                ]
            );
            $i2 = \dom::create_element(
                'i',
                [
                    'class'         => 'icon-trash cursor-pointer delete-menu-item-icon
                                margin-left-1',
                    'data-params'   => $el['id']
                ]
            );
            $td = \dom::create_element(
                'td',
                [
                    'class' => 'text-align-right',
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

        if($id) {
            $params['filter'] = '';
        }
        else {
            $model = \menu::get_admin_model('menus');

            $arr = $model->get_menus();

            $spans = '';

            foreach($arr as $el) {
                $span = \dom::create_element(
                    'span',
                    [
                        'class'         => 'label float-left margin-2px cursor-pointer',
                        'text'          => $el['alias'],
                        'data-params'   => $el['id']
                    ]
                );

                $spans .= $span;
            }

            $params['filter'] = \dom::create_element(
                'div',
                [
                    'class' => 'menu-items-filter width-100 inline-block',
                    'text'  => $spans
                ]
            );
        }

        return \templator::getTemplate(
            'index',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'items'
        );
    }

    /**
     * @param $id
     * @param null $parent
     * @param null $self
     * @return string
     */
    public function get_items_list($id, $parent = null, $self = null)
    {
        $model = \menu::get_admin_model('items');

        $opts = '';

        $mm = \menu::get_admin_model('menus');
        $mmarr = $mm->get_menus();
        $mmel = '';

        if(!$id) {
            $id = $mmarr[0]['id'];
        }

        $arr = $model->get_items($id);

        foreach($mmarr as $el) {
            if($el['id'] == $id) {
                $mmel = $el;
            }
        }

        if(!$mmel) {
            $mmel = $mmarr[0];
        }

        $opt = \dom::create_element(
            'option',
            [
                'value' => '',
                'text'  => $mmel['alias']
            ]
        );
        $opts .= $opt;


        foreach($arr as $el) {

            if($el['id'] != $self) {
                if($el['id'] == $parent) {
                    $opt = \dom::create_element(
                        'option',
                        [
                            'value'     => $el['id'],
                            'text'      => $el['alias'],
                            'selected'  => ''
                        ]
                    );
                }
                else {
                    $opt = \dom::create_element(
                        'option',
                        [
                            'value' => $el['id'],
                            'text'  => $el['alias']
                        ]
                    );
                }
            }
            else {
                $opt = \dom::create_element(
                    'option',
                    [
                        'value' => '',
                        'text'  => $el['alias']
                    ]
                );
            }

            $opts .= $opt;
        }

        return \dom::create_element(
            'select',
            [
                'name'  => 'parent',
                'text'  => $opts,
                'class' => 'menu-item-parent-select'
            ]
        );
    }

    /**
     * @param null $id
     * @return string
     */
    public function get_menus_list($id = null)
    {
        $model = \menu::get_admin_model('menus');

        $arr = $model->get_menus();

        $opts = '';

        foreach($arr as $el)
        {
            if($el['id'] == $id) {
                $opt = \dom::create_element(
                    'option',
                    [
                        'value'     => $el['id'],
                        'text'      => $el['alias'],
                        'selected'  => ''
                    ]
                );
            }
            else {
                $opt = \dom::create_element(
                    'option',
                    [
                        'value' => $el['id'],
                        'text'  => $el['alias']
                    ]
                );
            }

            $opts .= $opt;
        }

        return \dom::create_element(
            'select',
            [
                'name'  => 'menu',
                'text'  => $opts,
                'class' => 'menu-item-menu-select'
            ]
        );
    }

    public function create()
    {
        if($_POST) {
            $model = \menu::get_admin_model('items');
            $lang = \menu::read_lang('items_page');
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
                $errors =  $model->create($_POST);

                if(is_array($errors)) {
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
                            'object'    => 'App',
                            'method'    => 'showNoty',
                            'params'    =>[$lang['item_created'], 'success']
                        ]
                    );

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'MenuView',
                            'method'    => 'update_items_table',
                            'params'    => []
                        ]
                    );
                }
            }

        }
        else {
            $params = \menu::read_lang('items_page');

            $model = \menu::get_admin_model('menus');
            $menus = $model->get_menus();
            if(count($menus) == 0) {
                return \templator::get_warning($params['no_menus']);
            }

            $params['menus']            = $this->get_menus_list();
            $params['menu_items_list']  = $this->get_items_list(null);

            $arr = \menu::get_urls();

            if(count($arr)) {
                $urls = '';

                foreach($arr as $el) {
                    $span = \dom::create_element(
                        'span',
                        [
                            'class'     => 'label float-left cursor-pointer margin-2px',
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

            $params['name']         = '';
            $params['alias']        = '';
            $params['HEADER']       = $params['CREATE_HEADER'];
            $params['BTN_LABEL']    = $params['CREATE_BTN_LABEL'];
            $params['task']         = 'create';

            return \templator::getTemplate(
                'create',
                $params,
                \menu::$path.'admin'.DS.'views'.DS.'items'
            );
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        if($id) {
            $model = \menu::get_admin_model('items');

            $model->delete($id);
        }
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function edit($id)
    {
        if($_POST) {
            $lang = \menu::read_lang('items_page');

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
                $model = \menu::get_admin_model('items');

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
                            'object'    => 'App',
                            'method'    => 'showNoty',
                            'params'    =>[$lang['item_edited'], 'success']
                        ]
                    );

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'MenuView',
                            'method'    => 'update_items_table',
                            'params'    => []
                        ]
                    );
                }
            }
        }
        else {
            $params = \menu::read_lang('items_page');
            $model = \menu::get_admin_model('items');

            $mm     = \menu::get_admin_model('menus');
            $menus  = $mm->get_menus();
            if(count($menus) == 0) {
                return \templator::get_warning($params['no_menus']);
            }

            $obj = $model->get($id);

            $params['menus'] = $this->get_menus_list($obj['menu']);
            $params['menu_items_list']  =
                $this->get_items_list($obj['menu'], $obj['parent'], $obj['id']);

            $arr = \menu::get_urls();
            $urls = '';
            foreach($arr as $el) {
                if($obj['url'] == $el['url']) {
                    $span = \dom::create_element(
                        'span',
                        [
                            'class' => 'label cursor-pointer label-info margin-2px',
                            'text'  => $el['url']
                        ]
                    );
                    $span .= \dom::create_element(
                        'input',
                        [
                            'class'     => 'hide',
                            'name'      => 'url',
                            'type'      => 'checkbox',
                            'value'     => $el['url'],
                            'checked'   => 'checked'
                        ]
                    );
                }
                else {
                    $span = \dom::create_element(
                        'span',
                        [
                            'class' => 'label cursor-pointer margin-2px',
                            'text'  => $el['url']
                        ]
                    );
                }

                $urls .= $span;
            }

            unset($obj['name']);
            $params['urls']         = $urls;
            $params['name']         = 'hide';
            $params['HEADER']       = $params['EDIT_HEADER'];
            $params['BTN_LABEL']    = $params['EDIT_BTN_LABEL'];
            $params = array_merge($params, $obj);
            $params['task']         = "edit&params=$id";

            return \templator::getTemplate(
                'create',
                $params,
                \menu::$path.'admin'.DS.'views'.DS.'items'
            );
        }
    }
}