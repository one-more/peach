<?php
/**
 * Class userwidget
 *
 * @author Nikolaev D.
 */
class userwidget extends supercontroller implements widget_controller_interface{

    use trait_extension_controller;

    /**
     * @var
     */
    public $extension;

    public function __construct()
    {
        $this->_cache_path = user::$path.'admin'.DS.'cache'.DS;
        $this->extension = 'user';
    }

    /**
     *
     */
    public function display()
    {
        $id = user::get_id();

        if(!$id) {
            return templator::get_warning(factory::get_reference('user')['NOT_IN_SYSTEM']);
        }

        if($this->get_cache_view('user_info_'.$id)) {
            $user = json_decode($this->get_cache_view('user_info_'.$id), true);
        }
        else{
            $model = user::get_admin_model('user');

            $user = $model->get($id, true);

            $this->set_cache_view('user_info_'.$id, json_encode($user));
        }

        $privs = factory::get_reference('privileges');

        $lang = user::read_lang('user_widget');

        $user = array_merge($user['user'], $user['info']);

        $params = array_merge($user, ['privileges' => $privs[$user['credentials']]], $lang);

        return templator::getTemplate('user', $params ,user::$path.'widget_views'.DS.'user');
    }

    /**
     *
     */
    public function update_avatar()
    {
        if(!empty($_REQUEST['avatar-data']) && !empty($_REQUEST['user-id'])) {
            $model = user::get_admin_model('user');

            $model->change_avatar(
                $_REQUEST['avatar-data'],
                $_REQUEST['user-id']
            );

            $this->delete_cache_view('user_info_'.$_REQUEST['user-id']);

            $controller = user::get_admin_controller('users');

            $controller->manage_avatars();
        }
    }

    /**
     * @return array|mixed
     */
    public function get_info()
    {
        $alias = $this->getLang('user_widget')['alias'];

        return ['alias'=>$alias, 'name'=>'user'];
    }
}