<?php

namespace articles_admin;

/**
 * Class categoriescontroller
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class categoriesmodel extends \superModel {

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $sth = $this->_db->prepare(
            "
                SELECT * FROM `{$lang}_article_categories`
                WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);

        $sth->execute();

        return $sth->fetch();
    }

    /**
     * @return array
     */
    public function get_categories()
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        return $this->get_all("{$lang}_article_categories");
    }

    /**
     * @param $name
     */
    public function create($name)
    {
        if(empty($name)) {
            return;
        }

        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $sth = $this->_db->prepare(
            "
             SELECT * FROM `{$lang}_article_categories`
             WHERE `name` = ?
            "
        );
        $sth->bindParam(1, $name);
        $sth->execute();
        $sth = $sth->fetch();

        if(!$sth) {
            $sth    = $this->_db->prepare(
                "
                INSERT INTO `{$lang}_article_categories` SET
                `name` = ?
            "
            );
            $sth->bindParam(1, $name, \PDO::PARAM_STR);
            $sth->execute();
        }
    }
}