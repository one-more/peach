<?php

namespace articles_admin;

/**
 * Class tagsmodel
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class tagsmodel extends \superModel {

    /**
     * @param null $article
     * @return array
     */
    public function get_tags($article = null)
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        if(!$article) {
            return $this->get_all("{$lang}_article_tags");
        }
        else {
            $sth = $this->_db->prepare(
                "
                    SELECT * FROM `{$lang}_article_tags`
                    WHERE `id` in
                    (
                      SELECT `tag` FROM `{$lang}_articles_tags`
                      WHERE `article` = ?
                    )
                "
            );
            $sth->bindParam(1, $article);
            $sth->execute();

            return $sth->fetchAll();
        }
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
             SELECT * FROM `{$lang}_article_tags`
             WHERE `name` = ?
            "
        );
        $sth->bindParam(1, $name);
        $sth->execute();
        $sth = $sth->fetch();

        if(!$sth) {
            $sth = $this->_db->prepare(
                "
                INSERT INTO `{$lang}_article_tags` SET
                `name` = ?
            "
            );
            $sth->bindParam(1, $name, \PDO::PARAM_STR);
            $sth->execute();
        }
    }
}