<?php

/**
 * Interface site_template_interface
 *
 * @author Nikolaev D.
 */
interface site_template_interface extends super_interface {
    /**
     * must return array of positions
     *
     * @return mixed
     */
    public static function get_positions();

    /**
     * must return array: alias, name, [preview], [icon]
     * @return mixed
     */
    public static function get_info();
}