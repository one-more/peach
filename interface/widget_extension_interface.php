<?php
/**
 * Class widget_extension_interface
 *
 * @author Nikolaev D.
 */
interface widget_extension_interface extends super_interface {
    /**
     * @return mixed
     */
    public static function get_widgets();

    /**
     * @param $class
     * @return mixed
     */
    public static function get_widget($class);
}