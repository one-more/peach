<?php

/**
 * Class modulemodel
 *
 * @author Nikolaev D.
 */
class modulemodel extends superModel{
    /**
     * @param $data
     * @throws Exception
     */
    public function install_extension($data)
    {
        $error = static::check($data, [
            'name'          => 'not_empty',
            'is_daemon'     => 'not_empty',
            'cms_version'   => 'not_empty'
        ]);
        if($error) {
            throw new Exception('wrong ini file');
        }

        if($data['is_daemon'] === "true") {

            if(empty($data['daemon_type']) ||
                !in_array($data['daemon_type'], ['admin', 'site'])) {
                throw new Exception('wrong daemon type ');
            }
        }

        $sth = $this->_db->prepare('select * from extensions where `name` = ?');
        $sth->bindParam(1, $data['name']);
        $sth->execute();

        if($sth->fetch()) {
            throw new Exception('such extension already exists');
        }

        if(!system::accept_cms_version('extension', $data['cms_version'])) {
            throw new Exception('cms version does not accepted');
        }

        if(gettype($data['is_daemon']) != 'boolean') {
            $data['is_daemon'] =
                ($data['is_daemon'] === 'true' || $data['is_daemon'] == 1) ? true : false;
        }

        $sth = $this->_db->prepare('
                    insert into `extensions` SET
                    `name`      = ?,
                    `is_daemon` = ?
                '
        );
        $is_daemon = $data['is_daemon']? 1 : 0;
        $sth->bindParam(1, $data['name']);
        $sth->bindParam(2, $is_daemon);
        $sth->execute();

        if($data['is_daemon']) {

            $id = $this->_db->lastInsertId();

            $sth = $this->_db->prepare(
                '
                    insert into `daemons` SET
                     `extension`  = ?,
                     `type`       = ?
                '
            );
            $sth->bindParam(1, $id);
            $sth->bindParam(2, $data['daemon_type']);
            $sth->execute();
        }
    }

    /**
     * @param $data
     * @throws Exception
     */
    public function install_template($data)
    {
        $error = static::check($data, [
            'name'          => 'not_empty',
            'template_type' => 'not_empty',
            'cms_version'   => 'not_empty'
        ]);

        if($error) {
            throw new Exception('wrong ini file');
        }

        if(!system::accept_cms_version('template', $data['cms_version'])) {
            throw new Exception('cms version does not accepted');
        }

        $sth = $this->_db->prepare(
            'select * from `templates` where `name` = ?'
        );
        $sth->bindParam(1, $data['name']);
        $sth->execute();

        if($sth->fetch()) {
            throw new Exception('such template already installed');
        }

        $sth = $this->_db->prepare(
            '
                insert into `templates` SET
                    `name`  = ?,
                     `type` = ?
            '
        );
        $sth->bindParam(1, $data['name']);
        $sth->bindParam(2, $data['template_type']);
        $sth->execute();
    }

    /**
     * @param $data
     * @throws Exception
     */
    public function install_editor($data)
    {
        if(empty($data['name'])) {
            throw new Exception('empty editor name');
        }

        $sth = $this->_db->prepare(
            'select * from `editors` where `name` = ?'
        );
        $sth->bindParam(1, $data['name']);
        $sth->execute();

        if($sth->fetch()) {
            throw new Exception('such editor already installed');
        }

        $sth = $this->_db->prepare(
            '
            insert into `editors` SET
                `name` = ?
            '
        );
        $sth->bindParam(1, $data['name']);
        $sth->execute();
    }
}