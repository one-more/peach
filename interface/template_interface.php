<?php
/**
 * this interface must implement all of the templates
 *
 * Class template_interface
 *
 * @author Nikolaev D.
 */
interface template_interface extends super_interface {
    /**
     * must return array: alias, name, [preview], [icon]
     * @return mixed
     */
    public static function get_info();
}