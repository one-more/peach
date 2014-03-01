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
     * @return mixed
     */
    public static function get_create_layout_html();

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

    /**
     * @return mixed
     */
    public static function get_version();

    /**
     * @return array ['alias', 'name', [icon], [submenu]]
     */
    public static function get_info();
}