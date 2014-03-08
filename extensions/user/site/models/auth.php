<?php

namespace user_site;

/**
 * Class authmodel
 *
 * @package user_site
 *
 * @author Nikolaev D.
 */
class authmodel extends \superModel {

    /**
     * @param $arr
     * @return array
     */
    public function auth($arr)
    {
        $default = [
            'login'     => '',
            'password'  => ''
        ];

        $data = array_merge($default, $arr);

        $errors = static::check($data, [
           'login'      => 'not_empty',
            'password'  => 'not_empty'
        ]);

        if($errors) {
            return $errors;
        }

        $data['password'] = crypt($data['password'], 'the best ever');

        $sth = $this->_db->prepare(
            "
                SELECT * FROM `users`
                WHERE `login`  = ?
                AND `password` = ?
            "
        );
        $sth->bindParam(1, $data['login']);
        $sth->bindParam(2, $data['password']);
        $sth->execute();

        if($sth = $sth->fetch()) {
            return $sth['id'];
        }
        else {
            return false;
        }
    }
}