<?php
namespace menu_admin;

/**
 * Class menuscontroller
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class menuscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        $params = \menu::read_lang('menus_page');

        $model = \menu::get_admin_model('menus');
        $arr = $model->get_menus();

        if(count($arr)) {
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

                $i1 = \dom::create_element(
                    'i',
                    [
                        'class'         => 'icon-edit cursor-pointer
                            edit-menu-icon',
                        'data-params'   => $el['id']
                    ]
                );
                $i2 = \dom::create_element(
                    'i',
                    [
                        'class' => 'icon-trash cursor-pointer delete-menu-icon
                        margin-left-1',
                        'data-params'   => $el['id']
                    ]
                );
                $td = \dom::create_element(
                    'td',
                    [
                        'text'  => $i1.$i2,
                        'class' => 'text-align-right'
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
        }
        else {
            $params['trs'] = '';
        }

        return \templator::getTemplate(
            'index',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'menus'
        );
    }

    public function create()
    {
        if($_POST) {
            $model = \menu::get_admin_model('menus');

            $errors = $model->create($_POST);

            $lang = \menu::read_lang('menus_page');

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
                        'params'    => [$lang['menu_created'], 'success']
                    ]
                );

                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'MenuView',
                        'method'    => 'update_menus_table',
                        'params'    => []
                    ]
                );
            }
        }
        else {
            $params = \menu::read_lang('menus_page');

            return \templator::getTemplate(
                'create',
                $params,
                \menu::$path.'admin'.DS.'views'.DS.'menus'
            );
        }
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function edit($id)
    {
        if($_POST) {
            $model = \menu::get_admin_model('menus');

            $errors = $model->update($_POST, $id);

            $lang = \menu::read_lang('menus_page');

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
                        'params'    => [$lang['menu_updated'], 'success']
                    ]
                );

                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'MenuView',
                        'method'    => 'update_menus_table',
                        'params'    => []
                    ]
                );
            }
        }
        else {
            $params = \menu::read_lang('menus_page');
            $params['id'] = $id;

            $model = \menu::get_admin_model('menus');
            $obj = $model->get($id);
            $params['alias'] = $obj['alias'];

            return \templator::getTemplate(
                'edit',
                $params,
                \menu::$path.'admin'.DS.'views'.DS.'menus'
            );
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $model = \menu::get_admin_model('menus');

        $model->delete($id);
    }
}