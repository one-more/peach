<?php
namespace admin_admin;

/**
 * Class optionscontroller
 * @package admin_admin
 * @author Nikolaev D.
 */
class optionscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        return \templator::get_stub();
    }
}