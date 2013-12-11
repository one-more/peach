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
                'avatar'        => '/media/images/noavatar.gif'
            ];

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
}