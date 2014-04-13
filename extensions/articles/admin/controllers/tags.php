<?php

namespace articles_admin;

/**
 * Class tagscontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class tagscontroller extends \supercontroller implements \view_controller_interface {

    /**
     * @param null $id
     */
    public function get_tags($id = null)
    {
        $model = \articles::get_admin_model('tags');

        return $model->get_tags($id);
    }

    /**
     * @param $name
     */
    public function create($name)
    {
        $model = \articles::get_admin_model('tags');

        $model->create($name);
    }

    /**
     * @return array|mixed
     */
    public function get_info()
    {
        return \articles::read_lang('tags_view');
    }

    public function display()
    {
        $menu   = \system::get_menu();

        if($menu == -1) {
            return \templator::get_warning(
                \factory::get_reference('errors')['no_menu']
            );
        } else {

            $params                 = \articles::read_lang('tags_view');

            $params['html']         = $menu::get_create_layout_html();

            $params['class']        = 'articles';
            $params['controller']   = 'tags';
            $params['extension']    = \articles::get_info()['alias'];
            $params['action']       = "index.php?class={$menu}&method=create_layout";

            return \templator::getTemplate(
                'index',
                $params,
                \articles::$path.'admin'.DS.'views'.DS.'tags'
            );
        }
    }
}