<?php

namespace articles_site;

/**
 * Class searchcontroller
 *
 * @package articles_site
 *
 * @author Nikolaev D
 */
class searchcontroller extends  \supercontroller {

    /**
     * @param $word
     * @return string
     */
    public function display($word)
    {
        $controller = \articles::get_site_controller('blog');
        $model  = \articles::get_admin_model('articles');

        $params = [];
        $params['category'] = '';
        $params['limit']    = 5;

        $articles   = $model->search(
            $word,
            0,
            $params['limit']
        );

        if(count($articles) == 0) {

            $lang = \articles::read_lang('search_view');

            $h = \dom::create_element(
                'h3',
                [
                    'text'  => $lang['not_found']." \"{$word}\""
                ]
            );

            return \dom::create_element(
                'div',
                [
                    'class' => 'articles-article',
                    'text'  => $h
                ]
            );
        }

        $all = $controller->render_articles($params, $articles);

        return $all;
    }

    /**
     * @param $arr
     * @return string
     */
    public function show_more($arr)
    {
        $arr['word']    = rawurldecode($arr['word']);

        $controller = \articles::get_site_controller('blog');
        $model  = \articles::get_admin_model('articles');

        $params = [];
        $params['category'] = '';
        $params['limit']    = 5;

        $articles   = $model->search(
            $arr['word'],
            $arr['id'],
            $params['limit']
        );

        if(count($articles) == 0) {
            return '';
        }

        $all = $controller->render_articles($params, $articles);

        return $all;
    }
}