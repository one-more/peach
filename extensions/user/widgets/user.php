<?php
/**
 * Class userwidget
 *
 * @author Nikolaev D.
 */
class userwidget extends supercontroller {
    /**
     *
     */
    public function display()
    {
        $user = user::get();

        $user = json_decode($user, true);

        $user = array_merge($user['user'], $user['info']);

        $params = array_merge($user, []);

        return templator::getTemplate('user', $params ,user::$path.'widget_views'.DS.'user');
    }
}