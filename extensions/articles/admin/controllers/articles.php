<?php

namespace articles_admin;

/**
 * Class articlescontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class articlescontroller extends \supercontroller {

    /**
     * @param null $category
     * @return string
     */
    public function display($category = null)
    {
        $params = \articles::read_lang('articles_page');

        $model  = \articles::get_admin_model('articles');

        $arr = $model->get_articles($category);

        $trs = '';

        foreach($arr as $el) {
            $tr = '';

            $td = \dom::create_element(
                'td',
                [
                    'text' => $el['id']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text' => $el['title']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['category']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['date']
                ]
            );
            $tr .= $td;

            $td = \dom::create_element(
                'td',
                [
                    'text'  => $el['author']
                ]
            );
            $tr .= $td;

            $img = \dom::create_element(
                'img',
                [
                    'class' => 'cursor-pointer articles-publish',
                    'src'   => $el['published'] ?
                            '/media/images/ok.png' :
                            '/media/images/bullet.png',
                    'data-params'   => $el['id']
                ]
            );
            $td = \dom::create_element(
                'td',
                [
                    'text'  => $img
                ]
            );
            $tr .= $td;

            $i1 = \dom::create_element(
                'i',
                [
                    'class' => 'icon-edit cursor-pointer articles-edit-btn',
                    'data-params' => $el['id']
                ]
            );

            $i2 = \dom::create_element(
                'i',
                [
                    'class' => 'icon-trash cursor-pointer articles-delete-btn',
                    'data-params' => $el['id']
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

        $model = \articles::get_admin_model('categories');

        $cats = $model->get_categories();
        $filter = '';

        foreach($cats as $el) {
            $filter .= \dom::create_element(
                'span',
                [
                    'text'          => $el['name'],
                    'data-params'   => $el['id'],
                    'class'         => 'label margin-2px cursor-pointer'
                ]
            );
        }

        $params['filter'] = $filter;

        return \templator::getTemplate(
            'index',
            $params,
            \articles::$path.'admin'.DS.'views'.DS.'articles'
        );
    }

    public function create()
    {
        if($_POST) {

            $error = false;
            $params = \articles::read_lang('articles_page');

            if(empty($_POST['category'])) {
                $error = $params['select_cat'];
            }

            $_POST['text'] = trim($_POST['text']);
            if(empty($_POST['text'])) {
                $error = $params['type_text'];
            }

            $_POST['tags'] = trim($_POST['tags']);
            if(empty($_POST['tags'])) {
                $error = $params['select_tags'];
            }

            if($error) {
                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'showNoty',
                        'params'    => [$error, 'error']
                    ]
                );
            }
            else {
                $model = \articles::get_admin_model('articles');

                $error = $model->create($_POST);

                if(is_array($error)) {
                    return ['error' => $error];
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
                            'params'    => [$params['created'], 'success']
                        ]
                    );

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'ArticlesPageView',
                            'method'    => 'update_table'
                        ]
                    );
                }
            }
        }
        else {

            $params = \articles::read_lang('articles_page');
            $params['task'] = 'create';
            $params['LEGEND'] = $params['CREATE_LEGEND'];
            $params['BTN_LABEL'] = $params['CREATE_BTN_LABEL'];


            $model = \articles::get_admin_model('categories');
            $cats = $model->get_categories();

            $opts = '';
            foreach($cats as $el) {
                $opts .= \dom::create_element(
                    'option',
                    [
                        'value' => $el['id'],
                        'text'  => $el['name']
                    ]
                );
            }

            $params['cats']         = $opts;
            $params['tags']         = '';
            $params['text']         = '';
            $params['title']        = '';
            $params['published']    = "checked";

            return \templator::getTemplate(
                'create',
                $params,
                \articles::$path.'admin'.DS.'views'.DS.'articles'
            );
        }
    }

    public function update($id)
    {
        $model = \articles::get_admin_model('articles');

        if($_POST) {
            $error = false;
            $params = \articles::read_lang('articles_page');

            if(empty($_POST['category'])) {
                $error = $params['select_cat'];
            }

            $_POST['text'] = trim($_POST['text']);
            if(empty($_POST['text'])) {
                $error = $params['type_text'];
            }

            $_POST['tags'] = trim($_POST['tags']);
            if(empty($_POST['tags'])) {
                $error = $params['select_tags'];
            }

            if($error) {
                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'showNoty',
                        'params'    => [$error, 'error']
                    ]
                );
            }
            else {

                $_POST['id'] = $_REQUEST['id'];
                $error = $model->update($_POST);

                if(is_array($error)) {
                    return ['error' => $error];
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
                            'params'    => [$params['updated'], 'success']
                        ]
                    );

                    \comet::add_message(
                        [
                            'task'      => 'delegate',
                            'object'    => 'ArticlesPageView',
                            'method'    => 'update_table'
                        ]
                    );
                }
            }
        }
        else {
            $obj = $model->get($id);

            $params = \articles::read_lang('articles_page');
            $params['task'] = "update&id={$id}";
            $params = array_merge($params, $obj);
            $params['LEGEND'] = $params['EDIT_LEGEND'];
            $params['BTN_LABEL'] = $params['EDIT_BTN_LABEL'];
            $params['published'] = $params['published']?'checked':'';

            $model = \articles::get_admin_model('categories');
            $cats = $model->get_categories();

            $opts = '';
            foreach($cats as $el) {
                if($el['id'] == $params['category']) {
                    $opts .= \dom::create_element(
                        'option',
                        [
                            'value' => $el['id'],
                            'text'  => $el['name'],
                            'selected' => ''
                        ]
                    );
                }
                else {
                    $opts .= \dom::create_element(
                        'option',
                        [
                            'value' => $el['id'],
                            'text'  => $el['name']
                        ]
                    );
                }
            }

            $params['cats'] = $opts;

            return \templator::getTemplate(
                'create',
                $params,
                \articles::$path.'admin'.DS.'views'.DS.'articles'
            );
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $model = \articles::get_admin_model('articles');

        $model->delete($id);
    }

    /**
     * @param $id
     */
    public function publish($id)
    {
        $model = \articles::get_admin_model('articles');

        $model->publish($id);
    }
}