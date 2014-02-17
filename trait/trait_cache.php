<?php

/**
 * Class trait_cache
 *
 * @author Nikolaev D.
 */
trait trait_cache {

    /**
     * @throws Exception
     */
    public static function check_dir()
    {
        if(!static::$cache_path) {
            throw new Exception('variable $cache_path does not exists in class '.get_called_class());
        }
        else {
            if(!is_dir(static::$cache_path)) {
                mkdir(static::$cache_path);
            }
        }
    }

    /**
     * clear cache files
     */
    public static  function clear_cache($now = false)
    {
        static::check_dir();

        $iterator = new FilesystemIterator(static::$cache_path);

        foreach($iterator as $el) {
            if(!$now) {
                if(time() > filemtime($el) + 3600*24*7) {
                    unlink($el);
                }
            }
            else {
                unlink($el);
            }
        }
    }
}