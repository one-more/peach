<?php
/**
 * Class authmodel
 *
 * @author Nikolaev D.
 */
class authmodel extends superModel {
    public function auth($data)
    {
        try{
            $data['password'] = crypt($data['password'], 'the best ever');

            $sth = $this->_db->prepare('select * from `users` where `login` = ? and password = ?');
            $sth->bindParam(1, $data['login']);
            $sth->bindParam(2, $data['password']);
            $sth->execute();

            $sth = $sth->fetch();

            if($sth) {
                return $sth['id'];
            }
            else {
                $ini = user::get_admin_controller('auth')->getLang('errors');

                return ['message' => $ini['auth_error']];
            }
        }
        catch(PDOException $e) {
            error::log($e->getMessage());

            error::show_error();
        }
    }
}