<?php
namespace system_site;

/**
 * Class defaultcontroller
 *
 * @package system_site
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends \supercontroller {

    /**
     * @return array|bool|string
     */
    public function get_options()
    {
        return \system::read_params('options');
    }
}