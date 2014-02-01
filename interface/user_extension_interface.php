<?php
/**
 * this interface must implement all extensions except system
 *
 * Class user_extension_interface
 *
 * @author Nikolaev D.
 */
interface user_extension_interface extends super_interface {
    /**
     * must return array: alias, name, [icon], [submenu]
     *
     * @return mixed
     */
    public static function get_info();
}