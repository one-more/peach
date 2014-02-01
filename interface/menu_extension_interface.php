<?php
/**
 * this interface must implement menu extensions
 *
 * Class menu_extension_interface
 *
 * @author Nikolaev D.
 */
interface menu_extension_interface extends super_interface {
    /**
     * parameters must be passed in the $_POST array
     *
     * mandatory parameters: name, alias, class, controller, extension;
     * other parameters - layout settings
     *
     * @return mixed
     */
    public static  function create_layout();

    /**
     * @param $id
     * @return mixed
     */
    public static function get_layout_params($id);

    /**
     * @param $link
     * @return mixed
     */
    public static function get_page($link);
}