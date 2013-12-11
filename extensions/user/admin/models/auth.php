<?php
namespace user_admin;
/**
 * Class authmodel
 *
 * @author Nikolaev D.
 */
class authmodel extends \superModel {
    public function auth($data)
    {
        try{
            $data['password'] = crypt($data['password'], 'the best ever');

            $sth = $this->_db->prepare('select * from `users` where `login` = ? and password = ?');
            $sth->bindParam(1, $data['login']);
            $sth->bindParam(2, $data['password']);
            $sth->execute();

            $sth = $sth->fetch();

            $ini = \user::get_admin_controller('auth')->getLang('errors');

            if($sth) {
                if($sth['credentials'] == 'SUPER_ADMIN' || $sth['credentials'] == 'ADMIN')
                {
                    return $sth['id'];
                }
                else {
                    return ['message' => $ini['wrong_credentials']];
                }
            }
            else {
                return ['message' => $ini['auth_error']];
            }
        }
        catch(PDOException $e) {
            \error::log($e->getMessage());

            \error::show_error();
        }
    }
}