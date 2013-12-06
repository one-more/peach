<?php
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends supercontroller{
    /**
     * @return mixed|string
     */
    public function display()
    {
        return templator::getTemplate(
            'index',
            null,
            documentation::$path.'admin'.DS.'views'.DS.site::getLang().DS.'default'
        );
    }
}