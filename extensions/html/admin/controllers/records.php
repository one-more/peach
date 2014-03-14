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
                    'class' => 'text-right'
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

        }
        else {
            $params = \html::read_lang('records_page');

            $params['name']         = '';
            $params['action']       = 'create';
            $params['alias']        = '';
            $params['HEADER']       = $params['CREATE_HEADER'];
            $params['BTN_LABEL']    = $params['CREATE_BTN_LABEL'];
            $params['task']         = 'create';

            return \templator::getTemplate(
                'create',
                $params,
                \html::$path.'admin'.DS.'views'.DS.'records'
            );
        }
    }
}