<?php
/**
 * Class editor_interface
 *
 * @author Nikolaev D.
 */
interface editor_interface extends super_interface {
    /**
     * @return mixed
     */
    public static function get_css();

    /**
     * @return mixed
     */
    public static function get_js();
}