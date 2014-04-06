<?php

namespace articles_admin;

/**
 * Class categoriescontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class categoriescontroller extends \supercontroller {

    /**
     * @param $name
     */
    public function create($name)
    {
        $model = \articles::get_admin_model('categories');

        $model->create($name);
    }
}