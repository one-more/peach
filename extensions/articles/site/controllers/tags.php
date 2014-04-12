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

        $controller = \articles::get_site_controller('blog');
        $model  = \articles::get_admin_model('articles');

        $articles = $model->get_articles_with_tag($arr['params'], 0, 5);

        $params = [];
        $params['category'] = '';
        $params['limit']    = 5;

        if(count($articles) == 0) {
            return '';
        }

        $all = $controller->render_articles($params, $articles);

        return $all;
    }
}