<?php

namespace articles_admin;

/**
 * Class blogcontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class blogcontroller extends \supercontroller
    implements \view_controller_interface {

    /**
     * @return array|mixed
     */
    public function get_info()
    {
        return \articles::read_lang('blog_view');
    }

    /**
     * @return string
     */
    public function display()
    {
        $model = \articles::get_admin_model('categories');

        $arr = $model->get_categories();

        $params = \articles::read_lang('blog_view');

        $opts = \dom::create_element(
            'option',
            [
                'value' => 0,
                'text'  => $params['all_cats']
            ]
        );

        foreach($arr as $el) {
            $opts .= \dom::create_element(
                'option',
                [
                    'value' => $el['id'],
                    'text'  => $el['name']
                ]
            );
        }

        $params['opts'] = $opts;

        $menu = \system::get_menu();

        if($menu == -1) {
            return \templator::get_warning(
                \factory::get_reference('errors')['no_menu']
            );
        }

        $params['html']         = $menu::get_create_layout_html();
        $params['action']       =
            "index.php?class={$menu}&method=create_layout";
        $params['class']        = 'articles';
        $params['extension']    = \articles::get_info()['alias'];
        $params['controller']   = 'blog';

        return \templator::getTemplate(
            'index',
            $params,
            \articles::$path.'admin'.DS.'views'.DS.'blog'
        );
    }
}