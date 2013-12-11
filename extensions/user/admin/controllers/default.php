<?php
namespace user_admin;

/**
 * Class defaultcontroller
 * @package user_admin
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {
    public function display()
    {
        return \templator::getTemplate(
            'section_under_construction',
            null,
            '..'.DS.'html'
        );
    }
}