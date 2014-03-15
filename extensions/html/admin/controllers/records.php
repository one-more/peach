<?php

namespace html_admin;

/**
 * Class recordscontroller
 *
 * @package html_admin
 *
 * @author Nikolaev D.
 */
class recordscontroller extends \supercontroller {

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \html::read_lang('records_page');

        $model  = \html::get_admin_model('records');
        $arr    = $model->get_records();

        $trs    = '';
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
                        edit-html-record-icon',
                    'data-params'   => $el['id']
                ]
            );
            $i2 = \dom::create_element(
                'i',
                [
                    'class'         => 'icon-trash cursor-pointer
                        delete-html-record-icon',
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
        $params['trs']  = $trs;

        return \templator::getTemplate(
            'index',
            $params,
            \html::$path.'admin'.DS.'views'.DS.'records'
        );
    }

    /**
     * @return mixed|string
     */
    public function create()
    {
        if($_POST) {
            $params = \html::read_lang('records_page');

            if(empty($_POST['text'])) {
                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'showNoty',
                        'params'    => [$params['empty_text'], 'error']
                    ]
                );
            }
            else {
                $model  = \html::get_admin_model('records');

                $errors = $model->create($_POST);

                if($errors) {
                    return ['error' => $errors];
                }
                else {
                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'App',
                            'method'    => 'showNoty',
                            'params'    => [$params['record_created'], 'success']
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

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'HtmlView',
                            'method'    => 'update_records_table',
                            'params'    => []
                        ]
                    );
                }
            }
        }
        else {
            $params = \html::read_lang('records_page');

            $params['name']         = '';
            $params['action']       = 'create';
            $params['alias']        = '';
            $params['HEADER']       = $params['CREATE_HEADER'];
            $params['BTN_LABEL']    = $params['CREATE_BTN_LABEL'];
            $params['task']         = 'create';
            $params['text']         = '';

            return \templator::getTemplate(
                'create',
                $params,
                \html::$path.'admin'.DS.'views'.DS.'records'
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
            $params = \html::read_lang('records_page');
            $model  = \html::get_admin_model('records');

            if(empty($_POST['text'])) {
                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'showNoty',
                        'params'    => [$params['empty_text'], 'error']
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
                            'method'    => 'showNoty',
                            'params'    => [$params['record_edited'], 'success']
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

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'HtmlView',
                            'method'    => 'update_records_table',
                            'params'    => []
                        ]
                    );
                }
            }
        }
        else {
            $params = \html::read_lang('records_page');
            $model  = \html::get_admin_model('records');
            $obj    = $model->get($id);

            $params['name']         = 'hide';
            $params['task']         = "edit&params={$id}";
            $params['alias']        = $obj['alias'];
            $params['HEADER']       = $params['EDIT_HEADER'];
            $params['BTN_LABEL']    = $params['EDIT_BTN_LABEL'];
            $params['text']         = $obj['text'];

            return \templator::getTemplate(
                'create',
                $params,
                \html::$path.'admin'.DS.'views'.DS.'records'
            );
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $model = \html::get_admin_model('records');

        $model->delete($id);
    }
}