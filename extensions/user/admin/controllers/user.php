<?php
/**
 * Class usercontroller
 *
 * @author Nikolaev D.
 */
class usercontroller extends supercontroller {
    /**
     * @var string
     */
    private $_cache;

    /**
     * @var string
     */
    private $_cache_path;

    use trait_extension_controller;

    public function __construct()
    {
        $this->_cache_path = '..'.DS.'extensions'.DS.'user'.DS.'admin'.DS.'cache'.DS;

        $this->_cache = json_decode($this->get_cache_view('user'), true);
    }

    /**
     * @param $arr
     */
    public function create($arr)
    {
        $user = $arr['user'];

        $info = $arr['info'];

        $model = user::get_admin_model('user');

        return $model->create($user, $info);
    }

    /**
     * @return bool
     */
    public function is_super_admin()
    {
        if($this->_cache) {
            return $this->_cache['credentials'] == 'SUPER_ADMIN';
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
        if($this->_cache) {
            return $this->_cache['credentials'] == 'SUPER_ADMIN' || $this->_cache['credentials'] == 'ADMIN';
        }
        else {
            return false;
        }
    }
}