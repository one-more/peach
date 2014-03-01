<?php

/**
 * Class link_corrector
 *
 * @author Nikolaev D.
 */
class link_corrector implements daemon_extension_interface{
    use trait_extension;

    /**
     * @return mixed|void
     */
    public static function delete()
    {

    }

    /**
     * @return mixed|string
     */
    public static function get_version()
    {
        return '1.0';
    }

    /**
     * @return array|mixed
     */
    public static function get_js()
    {
        return [
          dom::create_element(
              'script',
              [
                  'src' => '/js/link_corrector/admin/modules/link_corrector.js'
              ]
          )
        ];
    }

    /**
     * @return array
     */
    public static function get_css()
    {
        return [];
    }

    /**
     * @return mixed|void
     */
    public static function get_info()
    {
        return static::read_lang('info');
    }
}