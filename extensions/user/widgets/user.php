<?php
/**
 * Class userwidget
 *
 * @author Nikolaev D.
 */
class userwidget extends supercontroller implements widget_controller_interface{

    use trait_extension_controller;

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

    /**
     * @return array|mixed
     */
    public function get_info()
    {
        $alias = $this->getLang('user_widget')['alias'];

        return ['alias'=>'alias', 'name'=>'user'];
    }
}