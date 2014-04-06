<?php

namespace articles_admin;

/**
 * Class tagscontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class tagscontroller extends \supercontroller {

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
}