<?php
/**
 * Class menu_extension_interface
 *
 * @author Nikolaev D.
 */
interface menu_extension_interface extends super_interface {
    /**
     * @param $position
     * @param $extension
     * @param $view
     * @param $params
     * @return mixed
     */
    public static  function create_layout($position, $extension, $view, $params);

    /**
     * @return mixed
     */
    public static  function get_items();
}