<?php
namespace documentation_admin;
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller{
    /**
     * @return mixed|string
     */
    public function display()
    {
        return \templator::get_stub();
    }
}