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
     * @param $arr -> ['position', 'extension', 'controller', 'params']
     * @return mixed
     */
    public static  function create_layout($arr);

    /**
     * @return mixed
     */
    public static  function get_items();

    /**
     * @param $arr -> ['url', 'params']
     * @return array in format:
     * [
     *     0 => ['position'=>'', 'extension'=>'', 'controller'=>'', 'params'=>'']
     * ]
     */
    public static function get_page($arr = null);
}