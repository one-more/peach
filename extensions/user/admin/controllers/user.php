<?php
namespace user_admin;
/**
 * Class usercontroller
 *
 * @author Nikolaev D.
 */
class usercontroller extends \supercontroller {
    /**
     * @var string
     */
    private $_cache;

    /**
     * @var string
     */
    private $_cache_path;

    use \trait_extension_controller;

    public function __construct()
    {
        $this->_cache_path = '..'.DS.'extensions'.DS.'user'.DS.'admin'.DS.'cache'.DS;
    }

    /**
     * @param $arr
     */
    public function create($arr)
    {
        $user = $arr['user'];

        $info = $arr['info'];

        $model = \user::get_admin_model('user');

        return $model->create($user, $info);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        if($cache = $this->get_cache_view('user_info_'.$id)) {
            return json_decode($cache, true);
        }
        else {
            $model = \user::get_admin_model('user');

            $cache = $model->get($id, true);

            $this->set_cache_view('user_info_'.$id, json_encode($cache));

            return $cache;
        }
    }

    /**
     * @return bool
     */
    public function is_super_admin()
    {
        if(\user::is_auth()) {
            $user = $this->get(\user::get_id());

            return $user['user']['credentials'] == 'SUPER_ADMIN';
        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function is_admin()
    {
        if(\user::is_auth()) {
            $user = $this->get(\user::get_id());

            return  $user['user']['credentials'] == 'ADMIN' ||
                    $user['user']['credentials'] == 'SUPER_ADMIN';
        }
        else {
            return false;
        }
    }
}