<?php
/**
 * this interface must implement all widget controllers
 *
 * Class widget_controller_interface
 *
 * @author Nikolaev D.
 */
interface widget_controller_interface {
    /**
     * must return array: alias, name
     *
     * @return mixed
     */
    public function get_info();
}