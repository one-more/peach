<?php

namespace articles_site;

/**
 * Class tagscontroller
 *
 * @package articles_site
 *
 * @author Nikolaev D.
 */
class tagscontroller extends \supercontroller {

    public function display($arr)
    {
        $arr['params'] = rawurldecode($arr['params']);

        $controller = \articles::get_site_controller('blog');
        $model  = \articles::get_admin_model('articles');

        $params = [];
        $params['category'] = '';
        $params['limit']    = 5;

        $articles = $model->get_articles_with_tag(
            $arr['params'],
            0,
            $params['limit']);

        if(count($articles) == 0) {
            return '';
        }

        $all = $controller->render_articles($params, $articles);

        return $all;
    }

    public function show_more($arr)
    {
        $controller = \articles::get_site_controller('blog');
        $model  = \articles::get_admin_model('articles');

        $params = [];
        $params['category'] = '';
        $params['limit']    = 5;

        $arr['tag'] = rawurldecode($arr['tag']);

        $articles = $model->get_articles_with_tag(
            $arr['tag'],
            $arr['id'],
            $params['limit']);

        if(count($articles) == 0) {
            return '';
        }

        $all = $controller->render_articles($params, $articles);

        return $all;
    }
}