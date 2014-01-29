<?php

/**
 * Class comet - is used to add messages to comet daemon
 *
 * @author Nikolaev D
 */
class comet {
    /**
     * @param $msg
     */
    public static function add_message($msg)
    {
        $arr = static::get_array();

        $arr[] = $msg;

        static::save($arr);
    }

    /**
     * @return mixed
     */
    public static function get_array()
    {
        if(!file_exists(SITE_PATH.'resources')) {
            mkdir(SITE_PATH.'resources');
        }
        if(!file_exists(SITE_PATH.'resources'.DS.'comet.db')) {
            file_put_contents(SITE_PATH.'resources'.DS.'comet.db', '');
        }

        return json_decode(file_get_contents(SITE_PATH.'resources'.DS.'comet.db'), true);
    }

    /**
     * @param $arr
     */
    private static function save($arr)
    {
        file_put_contents(SITE_PATH.'resources'.DS.'comet.db', json_encode($arr));
    }

    /**
     * clears file comet.db
     */
    public static function clear_array()
    {
        static::save([]);
    }
}