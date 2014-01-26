<?php
namespace user_admin;
/**
 * Class usermodel
 */
class usermodel extends \superModel {
    /**
     * @param $user
     * @param $info
     * @return string
     */
    public function create($user, $info)
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

            $this->get_reference();

            $user = \helper::purify($user);
            $info = \helper::purify($info);

            $merged_array = array_merge($user,$info);

            $errors = static::check($merged_array, [
                'login'     => ['not_empty', 'unique_user'],
                'email'     => 'email',
                'password'  => ['not_empty', 'password']
            ]);

            if($errors) {
                return $errors;
            }

            if($info['avatar']) {
                $avatar = \user::read_params('user');

                $info['avatar'] = \helper::make_img(
                    $info['avatar'],
                    '.'.DS.'media'.DS.'users_avatars',
                    DS.'media'.DS.'users_avatars',
                    $avatar['default_avatar_width'],
                    $avatar['default_avatar_height']
                );
            }

            $info = \helper::delete_empty_values($info);
            $info = array_merge($default, $info);

            $user['password'] = crypt($user['password'], 'the_best_ever');

            $sth = $this->_db->prepare('INSERT INTO `users` SET `login` = ?, `password` = ?, `credentials` = ?');
            $sth->bindParam(1, $user['login']);
            $sth->bindParam(2, $user['password']);
            $sth->bindParam(3, $user['credentials']);
            $sth->execute();

            $id = $this->_db->lastInsertId();

            $sth = $this->_db->prepare('
                INSERT INTO `user_info` SET
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
                ');
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
     * checks whether user exists
     *
     * @param $value
     * @return bool
     */
    public static function valid_unique_user($value)
    {
        static::$reference['user_exists'] = \user::read_lang('references')['user_exists'];

        $model = \user::get_admin_model('user');

        if($user = $model->get_by_login($value)) {
            return static::$reference['user_exists'];
        }
        else {
            return false;
        }
    }
}