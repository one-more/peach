<?php

/**
 * Class removemodel
 *
 * @author nikolaev D.
 */
class removemodel extends superModel {

    /**
     * @param $arr
     * @throws Exception
     */
    public function remove($arr)
    {
        if(empty($arr['name']) || empty($arr['type'])) {
            throw new Exception('cannot delete module - empty name or type');
        }

        switch($arr['type']) {
            case 'editor':
                $sth = $this->_db->prepare('delete from editors where name = ?');
                $sth->bindParam(1, $arr['name']);
                $sth->execute();
                $arr['name']::delete();
                break;
            case 'template':
                $sth = $this->_db->prepare('delete from templates where name = ?');
                $sth->bindParam(1, $arr['name']);
                $sth->execute();
                $arr['name']::delete();
                break;
            case 'extension':
                $sth = $this->_db->prepare('delete from extensions where name = ?');
                $sth->bindParam(1, $arr['name']);
                $sth->execute();
                $arr['name']::delete();
                break;
            case 'daemon':
                $sth = $this->_db->prepare('select * from extensions where name = ?');
                $sth->bindParam(1, $arr['name']);
                $sth->execute();
                $sth = $sth->fetch();
                $id = $sth['id'];
                $this->_db->query("delete from extensions where id = $id");
                $this->_db->query("delete from daemons where extension = $id");
                $arr['name']::delete();
                break;
            default:
                throw new Exception('unknown type to delete');
                break;
        }
    }
}