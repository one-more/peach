<?php
/**
 * Class user
 *
 * @author Nikolaev D.
 */
class user implements widget_extension_interface {
    use trait_extension, trait_widget_extension;

    /**
     * @param $user
     * @param $info
     */
    public static function create($user, $info)
    {
        $controller = static::get_admin_controller('user');

        return $controller->exec('create', ['user'=>$user, 'info'=>$info]);
    }
}