<?php

namespace articles_admin;

/**
 * Class articlecontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class articlecontroller extends \supercontroller
    implements \view_controller_interface{

    /**
     * @return string
     */
    public function display()
    {
        $menu = \system::get_menu();
        $params = \articles::read_lang('article_view');

        if($menu == -1) {
            return \templator::get_warning(
                \factory::get_reference('errors')['no_menu']
            );
        }
        else {
            $params['html'] = $menu::get_create_layout_html();
        }

        $params['class']   = 'articles';
        $params['extension']    = \articles::get_info()['alias'];
        $params['controller']   = 'article';
        $params['action']       =
            "index.php?class={$menu}&method=create_layout";

        $model = \articles::get_admin_model('articles');

        $arr = $model->get_articles(null, true);

        if(count($arr) == 0) {
            return \templator::get_warning(
                $params['no_articles']
            );
        }

        $opts = '';
        foreach($arr as $el) {
            $opt = \dom::create_element(
                'option',
                [
                    'value' => $el['id'],
                    'text'  => $el['title']
                ]
            );

            $opts .= $opt;
        }

        $params['opts'] = $opts;

        return \templator::getTemplate(
            'index',
            $params,
            \articles::$path.'admin'.DS.'views'.DS.'article'
        );
    }

    /**
     * @return array|mixed
     */
    public function get_info()
    {
        return \articles::read_lang('article_view');
    }
}