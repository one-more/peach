<?php
/**
 * this interface must implement all view controllers.
 *
 * Class view_controller_interface
 *
 * @author Nikolaev D.
 */
interface view_controller_interface {
    /**
     * must return array: alias, name, description
     *
     * @return mixed
     */
    public function get_info();
}