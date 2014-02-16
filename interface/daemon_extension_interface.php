<?php

/**
 * Interface daemon_extension_interface
 *
 * @author Nikolaev D.
 */
interface daemon_extension_interface extends super_interface {
    /**
     * @return mixed
     */
    public static function get_js();

    /**
     * must return array [alias, name, [icon], [submenu]]
     *
     * @return mixed
     */
    public static function get_info();
}