<?php
/**
 * Class usercontroller
 *
 * @author Nikolaev D.
 */
class usercontroller extends supercontroller {
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
}