<?php

class tablepagination implements daemon_extension_interface {

    use trait_extension;

    /**
     * @return array|mixed
     */
    public static function get_js()
    {
        return [
            dom::create_element(
                'script',
                [
                    'src'   => '/js/table_pagination/table_pagination.js'
                ]
            )
        ];
    }

    /**
     * @return array|mixed
     */
    public static function get_info()
    {
        return static::read_lang('info');
    }

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
}