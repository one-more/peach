<?php
namespace noop_admin;

/**
 * Class langcontroller
 *
 * @package noop_admin
 *
 * @author Nikolaev D.
 */
class langcontroller extends \supercontroller {

    /**
     * @return bool|\iniServer|null
     */
    public function get_model()
    {
        if(file_exists(SITE_PATH.'configuration.ini')) {
            return \factory::get_reference('lang_model');
        }
        else {
            return [];
        }
    }
}
