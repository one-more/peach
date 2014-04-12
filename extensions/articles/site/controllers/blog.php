<?php

namespace articles_site;

/**
 * Class blogcontroller
 *
 * @package articles_site
 *
 * @author Nikolaev D.
 */
class blogcontroller extends \supercontroller {

    /**
     * @param $arr
     * @return string
     */
    public function display($arr)
    {
        $model = \articles::get_admin_model('articles');

        $menu = \system::get_menu();

        if($menu != -1) {
            $params = $menu::get_layout_params($arr['id']);
            $offset = $params['per_page'];

            $offset = $offset ? $offset : 5;

            $articles = $model->get_articles(
                $params['category'],
                true,
                false,
                intval($offset)
            );

            if(count($articles) == 0) {
                return '';
            }

            $params['limit'] = intval($offset);

            $all = $this->render_articles($params, $articles);

            $hook = \dom::create_element(
                'span',
                [
                    'class' => 'css-hook invisible',
                    'text'  => '/css/articles/ladda/ladda.min.css'
                ]
            );
            $all .= $hook;

            $hook = \dom::create_element(
                'span',
                [
                    'class' => 'js-hook invisible',
                    'text'  => '/js/articles/ladda/spin.min.js'
                ]
            );
            $all .= $hook;

            $hook = \dom::create_element(
                'span',
                [
                    'class' => 'js-hook invisible',
                    'text'  => '/js/articles/ladda/ladda.js'
                ]
            );
            $all .= $hook;

            $hook = \dom::create_element(
                'span',
                [
                    'class' => 'js-hook invisible',
                    'text'  => '/js/articles/site/views/blog_view.js'
                ]
            );
            $all .= $hook;

            $hook = \dom::create_element(
                'span',
                [
                    'class' => 'js-hook invisible',
                    'text'  => '/js/articles/is_in_view_port/isInViewport.js'
                ]
            );
            $all .= $hook;

            return $all;
        }
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function get_full($id)
    {
        $model = \articles::get_admin_model('articles');

        $article =  $model->get($id);

        $time = strtotime($article['date']);
        $article['date'] = date('m F Y H:i', $time);

        $tags = explode(',', $article['tags']);

        $tag = '';
        foreach($tags as $el) {
            $tag .= \dom::create_element(
                    'a',
                    [
                        'href'  => "/tags/{$el}",
                        'text'  => $el
                    ]
                ).' ';
        }
        $article['tags'] = $tag;

        $article['text'] = preg_replace(
            '/<hr \/>\s+<hr \/>/',
            '',
            $article['text']);

        return \templator::getTemplate(
            'index',
            $article,
            \articles::$path.'site'.DS.'views'.DS.'article'
        );
    }

    public function show_more($params)
    {
        $model = \articles::get_admin_model('articles');



        $articles = $model->get_articles(
                                $params['category'],
                                true,
                                intval($params['id']),
                                intval($params['limit'])
        );

        if(count($articles) == 0) {
            return '';
        }

        return $this->render_articles($params, $articles);
    }

    /**
     * @param $params - [category, limit]
     * @param $articles
     * @return string
     */
    public function render_articles($params, $articles)
    {
        $last = $articles[count($articles)-1];
        $lang = \articles::read_lang('blog_view');

        $all = '';

        foreach($articles as $k=>&$el) {
            $text = preg_split('/<hr \/>\s+<hr \/>/', $el['text']);

            switch(count($text)) {
                case '2':
                    $el['text'] = $text[0];
                    $btn = \dom::create_element(
                        'button',
                        [
                            'class'         =>
                                'ladda-button art-show-more-btn',
                            'data-style'    => 'expand-right',
                            'text'          => $lang['more'],
                            'data-size'     => 'xs',
                            'data-params'   => $el['id'],
                            'data-number'   => $k
                        ]
                    );
                    $div = \dom::create_element(
                        'div',
                        [
                            'class' => 'text-left',
                            'text'  => $btn
                        ]
                    );
                    $el['text'] .= $div.\dom::create_element('br',[]);
                    break;
                case '3':
                    $el['text'] = $text[1];
                    $btn = \dom::create_element(
                        'button',
                        [
                            'class'         =>
                                'ladda-button art-show-more-btn',
                            'data-style'    => 'expand-right',
                            'text'          => $lang['more'],
                            'data-size'     => 'xs',
                            'data-params'   => $el['id'],
                            'data-number'   => $k
                        ]
                    );
                    $div = \dom::create_element(
                        'div',
                        [
                            'class' => 'text-left',
                            'text'  => $btn
                        ]
                    );
                    $el['text'] .= $div.\dom::create_element('br',[]);
                    break;
            }

            if($el['id'] != $last['id'])
            {
                $el['text'] .= \dom::create_element('hr',[]);
            }
            else {
                $el['text'] .= \dom::create_element(
                    'div',
                    [
                        'class' =>
                            'well articles-show-more-div cursor-pointer',
                        'text'  => 'show more',
                        'data-params'   => $last['id'],
                        'data-category' => $params['category'],
                        'data-offset'   => $params['limit'],
                        'data-number'   => $k
                    ]
                );
            }

            $tags = explode(',', $el['tags']);

            $tag = '';
            foreach($tags as $el1) {
                $tag .= \dom::create_element(
                        'a',
                        [
                            'href'  => "/tags/{$el1}",
                            'text'  => $el1
                        ]
                    ).' ';
            }
            $el['tags'] = $tag;

            $time = strtotime($el['date']);
            $el['date'] = date('m F Y H:i', $time);

            $all .= \templator::getTemplate(
                'index',
                $el,
                \articles::$path.'site'.DS.'views'.DS.'article'
            );
        }

        return $all;
    }
}