<?php
/**
 * Class admin
 *
 * @author Nikolaev D.
 */
class admin {
    use trait_extension
    {
        trait_extension::start as trait_start;
    }

    /**
     * start the user panel
     */
    public static function start()
    {
        if(!user::is_auth()) {
            user::auth('/admin');
        }
        else {
            static::trait_start();
        }
    }
}