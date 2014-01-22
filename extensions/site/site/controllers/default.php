<?php
namespace site_site;
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {
    /**
     * display template
     */
    public function display()
    {
        $options = \site::read_params('options');

        $options['template']::start();
    }

    /**
     * @return array|bool|string
     */
    public function get_options()
    {
        return \site::read_params('options');
    }
}