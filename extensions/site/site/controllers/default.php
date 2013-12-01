<?php
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends supercontroller {
    /**
     * display template
     */
    public function display()
    {
        $options = site::read_params('options');

        $options['template']::start();
    }
}