<?php
namespace menu_admin;

/**
 * Class linksmodel
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class linksmodel extends \superModel {

    /**
     * @param $url
     * @param string $type
     */
    public function add_update($url, $type = 'add')
    {
        $url = \helper::purify($url);
        if($url[0] != '/') {
            $url = '/'.$url;
        }

        if($type == 'add') {
            $sth = $this->_db->prepare("select * from `url` where `url`.`url` = ?");
            $sth->bindParam(1, $url, \PDO::PARAM_STR);
            $sth->execute();

            if($sth = $sth->fetch()) {
                $lang = \menu::read_lang('create_layout_page');

                return $lang['url_exists'];
            }
            else {
                $sth = $this->_db->prepare(
                    'insert into url SET url = ?'
                );
                $sth->bindParam(1, $url, \PDO::PARAM_STR);
                $sth->execute();
            }
        }
        else {
            $sth = $this->_db->prepare('select id from url where `url`.`url` = ?');
            $sth->bindParam(1, $url, \PDO::PARAM_STR);
            $id = $sth->fetch();

            $sth = $this->_db->prepare(
                'UPDATE url SET url = ? where id = ?'
            );
            $sth->bindParam(1, $url, \PDO::PARAM_STR);
            $sth->bindParam(2, $id);
            $sth->execute();
        }
    }

    /**
     * @param $url
     */
    public function add($url) {
        return $this->add_update($url);
    }

    /**
     * @param $url
     */
    public function update($url) {
        return $this->add_update($url, 'update');
    }

    /**
     * @param $url
     */
    public function remove($url) {
        $url = \helper::purify($url);

        $sth = $this->_db->prepare('delete from url where url = ?');
        $sth->bindParam(1, $url, \PDO::PARAM_STR);
        $sth->execute();
    }
}