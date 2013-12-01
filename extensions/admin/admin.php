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
     * @var array
     */
    public static $js_files = [
        '<script src="/js/admin/admin/models/admin_model.js"></script>',
        '<script src="/js/admin/admin/modules/admin.js"></script>'
    ];

    /**
     * start the user panel
     */
    public static function start()
    {
        if(!user::is_auth() || !user::is_admin()) {
            user::auth('/admin');
        }
        else {
            static::trait_start();
        }
    }
}