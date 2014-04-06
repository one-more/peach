<?php

namespace articles_site;

/**
 * Class articlecontroller
 *
 * @package articles_site
 *
 * @author Nikolaev D.
 */
class articlecontroller extends \supercontroller {


    /**
     * @param $arr
     * @return string
     */
    public function display($arr)
    {
        $menu = \system::get_menu();

        if($menu != -1) {
            $params = $menu::get_layout_params($arr['id']);

            $model = \articles::get_admin_model('articles');

            $article =  $model->get($params['article']);

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
    }
}