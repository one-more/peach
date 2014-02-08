<?php
/**
 * this interface must implement all of the templates
 *
 * Class template_interface
 *
 * @author Nikolaev D.
 */
interface admin_template_interface extends super_interface {
    /**
     * must return array: alias, name, [preview - path to image or directory], [icon]
     * @return mixed
     */
    public static function get_info();
}