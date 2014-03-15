<?php

/**
 * Class noop
 *
 * @author Nikolaev D.
 */
class noop {

use trait_extension;

    /**
     * @param $url
     * @return mixed
     */
    public function get_file_selection($url)
    {
        $_REQUEST['base_dir'] = $url;

        $controller = static::get_admin_controller('fileselect');

        return $controller->display();
    }
}