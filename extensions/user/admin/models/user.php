<?php
namespace user_admin;
/**
 * Class usermodel
 */
class usermodel extends \superModel {

    /**
     * @param $user
     * @param $info
     * @return array|string
     */
    public function create($user, $info)
    {
        return $this->create_edit($user, $info, null, 'create');
    }

    /**
     * @param $user
     * @param $info
     * @param $id
     * @return array|string
     */
    public function edit($user, $info, $id)
    {
        return $this->create_edit($user, $info, $id, 'edit');
    }

    /**
     * @param $user
     * @param $info
     * @param null $_id
     * @param string $type
     * @return array|string
     */
    public function create_edit($user, $info, $_id = null, $type = 'create')
    {
        try {
            $default = [
                'full_name'     => '-',
                'email'         => '-',
                'phone'         => '-',
                'icq'           => '-',
                'skype'         => '-',
                'site'          => '-',
                'facebook'      => '-',
                'twitter'       => '-',
                'avatar'        => DS.'media'.DS.'images'.DS.'noavatar.gif'
            ];

            if($type == 'create') {
                $task   = 'INSERT INTO';
                $where  = '';
                $where1 = '';
            }
            elseif($type == 'edit') {
                $task   = 'UPDATE';
                $where  = "WHERE id = $_id";
                $where1 = "WHERE user = $_id";

                $old_user = $this->get($_id, true);
                if(empty($user['password'])) {
                    $user['password'] = $old_user['user']['password'];
                }
                if(empty($info['avatar'])) {
                    $info['avatar'] = $old_user['info']['avatar'];
                }
            }

            $this->get_reference();

            $user = \helper::purify($user);
            $info = \helper::purify($info);

            $merged_array = array_merge($user,$info);

            if($type == 'create') {
                $checks = [
                    'login'     => ['not_empty', 'unique_user'],
                    'email'     => 'email',
                    'password'  => ['not_empty', 'password']
                ];
            }
            elseif($type == 'edit') {
                $checks = [
                    'login'     => ($old_user['user']['login'] == $merged_array['login'])?
                            'not_empty' : ['not_empty', 'unique_user'],
                    'email'     => 'email',
                    'password'  => ['not_empty', 'password']
                ];
            }

            $errors = static::check($merged_array, $checks);

            if($errors) {
                return $errors;
            }

            if((!empty($info['avatar']) && $type == 'create') ||
                ($type == 'edit' && !empty($info['avatar']) && $info['avatar'] != $old_user['info']['avatar'])
            ) {
                $avatar = \user::read_params('user');

                $info['avatar'] = \helper::make_img(
                    $info['avatar'],
                    DS.'media'.DS.'users_avatars',
                    $avatar['default_avatar_width'],
                    $avatar['default_avatar_height']
                );
            }

            $info = \helper::delete_empty_values($info);
            $info = array_merge($default, $info);

            if($type == 'create' || $user['password'] != $old_user['user']['password'])
                $user['password'] = crypt($user['password'], 'the_best_ever');

            $sth = $this->_db->prepare(
                "$task `users` SET
                    `login` = ?,
                    `password` = ?,
                    `credentials` = ?
                     $where"
            );
            $sth->bindParam(1, $user['login']);
            $sth->bindParam(2, $user['password']);
            $sth->bindParam(3, $user['credentials']);
            $sth->execute();

            $id = $this->_db->lastInsertId();
            $id = ($id) ? $id : $_id;

            $sth = $this->_db->prepare("
                $task `user_info` SET
                    `user`      = ?,
                    `full_name` = ?,
                    `email`     = ?,
                    `phone`     = ?,
                    `icq`       = ?,
                    `skype`     = ?,
                    `site`      = ?,
                    `facebook`  = ?,
                    `twitter`   = ?,
                    `avatar`    = ?
                     $where1
                ");
            $sth->bindParam(1, $id);
            $sth->bindParam(2, $info['full_name']);
            $sth->bindParam(3, $info['email']);
            $sth->bindParam(4, $info['phone']);
            $sth->bindParam(5, $info['icq']);
            $sth->bindParam(6, $info['skype']);
            $sth->bindParam(7, $info['site']);
            $sth->bindParam(8, $info['facebook']);
            $sth->bindParam(9, $info['twitter']);
            $sth->bindParam(10, $info['avatar']);
            $sth->execute();

            return $id;
        }
        catch(PDOException $e) {
            \error::log($e->getMessage());

            \error::show_error();
        }
    }

    /**
     * @param $id
     * @param bool $info
     * @return mixed
     */
    public function get($id, $info = false)
    {
        try{
            $sth = $this->_db->query("select * from users where id = $id");

            if($info) {
                $arr['user'] = $sth->fetch();

                $sth = $this->_db->query("select * from user_info where user = $id");

                $arr['info'] = $sth->fetch();

                return $arr;
            }
            else {
                return $sth->fetch();
            }
        }
        catch(PDOException $e) {
            \error::log($e->getMessage());

            \error::show_error();
        }
    }

    public function get_by_login($name, $info = false)
    {
        try{
            $sth = $this->_db->prepare('select * from `users` where `login` = ?');
            $sth->bindParam(1, $name, \PDO::PARAM_STR);

            $sth->execute();

            $result = $sth->fetch();

            if($result) {
                return $this->get($result['id'], $info);
            }
            else {
                return false;
            }
        }
        catch(\PDOException $e) {
            \error::log($e->getMessage());

            \error::show_error();
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        try{
            $user = $this->get($id, true);

            $sth = $this->_db->prepare('delete from users where id = ?');
            $sth->bindParam(1, $id, \PDO::PARAM_INT);
            $sth->execute();

            $sth = $this->_db->prepare('delete from user_info where `user` = ?');
            $sth->bindParam(1, $id, \PDO::PARAM_INT);
            $sth->execute();

            if($user['info']['avatar'] != \user::read_params('user')['default_avatar']) {
                unlink('.'.$user['info']['avatar']);
            }
        }
        catch(\PDOException $e) {
            \error::log($e->getMessage());

            \error::show_error();
        }
    }

    /**
     * @param $data
     * @param $id
     */
    public function change_avatar($data, $id) {
        try{
            $defs = \user::read_params('user');

            $img = \helper::make_img(
                $data,
                DS.'media'.DS.'users_avatars',
                $defs['default_avatar_width'],
                $defs['default_avatar_height']
            );

            $sth = $this->_db->prepare(
                "
                    UPDATE `user_info` SET
                     `avatar` = ?
                     where `user` = ?
                "
            );

            $sth->bindParam(1, $img);
            $sth->bindParam(2, $id);

            $sth->execute();
        }
        catch(\PDOException $e) {
            \error::log($e->getMessage());

            \error::show_error();
        }
    }

    /**
     * checks whether user exists
     *
     * @param $value
     * @return bool
     */
    public static function valid_unique_user($value)
    {
        $model = \user::get_admin_model('user');

        if($user = $model->get_by_login($value)) {
            return static::$reference['user_exists'];
        }
        else {
            return false;
        }
    }
}