<?php

namespace menu_site;

/**
 * Class menumodel
 *
 * @package menu_site
 *
 * @author NIkolaev D.
 */
class menumodel extends \superModel {

    /**
     * @param $id
     * @return mixed
     */
    public function get_menu($id) {
        $model = \menu::get_admin_model('items');

        $arr = $model->get_items($id);
        foreach($arr as $k=>$v) {
            if($v['parent'] != 0) {
                unset($arr[$k]);
            }
        }
        $tmp = $this->get_structure($arr, $id);

        return $tmp;
    }

    public function get_sub($arr, $needle)
    {
        $tmp = [];

        foreach($arr as $el) {
            if($needle['id'] == $el['parent']) {
                $tmp[] = $el;
            }
        }

        return $tmp ? $tmp : false;
    }

    public function get_structure($arr, $id)
    {
        $model  = \menu::get_admin_model('items');
        $all    = $model->get_items($id);

        foreach($arr as &$el) {
            if($sub = $this->get_sub($all, $el)) {
                $el['sub_menu'] = $this->get_structure($sub, $id);
            }
        }

        return $arr;
    }
}