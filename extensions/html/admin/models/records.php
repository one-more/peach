<?php

namespace html_admin;

/**
 * Class recordsmodel
 *
 * @package html_admin
 *
 * @author Nikolaev D.
 */
class recordsmodel extends \superModel {

    /**
     * @return array
     */
    public function get_records()
    {
        $arr = $this->get_all('html_records');

        $lang = \system::get_current_lang();
        foreach($arr as &$el) {
            $name   = $el['name'];
            $key    = "html_record_{$name}_alias";
            $sth    = $this->_db->query(
                "
                    SELECT `value` FROM `{$lang}`
                    WHERE `key` = '{$key}'
                "
            );
            $sth = $sth->fetch();
            $el['alias'] = $sth['value'];
        }

        return $arr;
    }

    /**
     * @param $param
     * @return mixed
     */
    public function get($param)
    {
        $sth = $this->_db->prepare(
            "
                SELECT * FROM `html_records`
                WHERE `name` = :param
                OR `id` = :param
            "
        );
        $sth->bindParam(':param', $param);
        $sth->execute();

        $obj = $sth->fetch();

        if($obj) {
            $lang   = \system::get_current_lang();
            $name   = $obj['name'];
            $key    = "html_record_{$name}_alias";
            $sth    = $this->_db->query(
                "
                    SELECT `value` FROM `{$lang}`
                    WHERE `key` = '{$key}'
                "
            );
            $sth    = $sth->fetch();
            $obj['alias']   = $sth['value'];
        }

        return $obj;
    }

    /**
     * @param $arr
     * @param string $type
     * @return array
     */
    public function create_update($arr, $type = 'create')
    {
        $default = [
            'name'  => '',
            'alias' => '',
            'text'  => ''
        ];

        $data = array_merge($default, $arr);

        $data['name']   = preg_replace('/\s/', '_', $data['name']);

        $errors = static::check(
            $data,
            [
                'name'  =>  $type == 'create' ? ['not_empty', 'unique'] : 'not_empty',
                'text'  => 'not_empty',
                'alias' => 'not_empty'
            ]
        );

        if($errors) {
            return $errors;
        }

        $lang   = \system::get_current_lang();
        $name   = $data['name'];
        $key    = "html_record_{$name}_alias";

        $sth    = $this->_db->prepare(
            "
                INSERT INTO `{$lang}` SET
                `key`   = :key,
                `value` = :value
                ON DUPLICATE KEY UPDATE
                `value` = :value
            "
        );
        $sth->bindParam(':key', $key);
        $sth->bindParam(':value', $data['alias']);
        $sth->execute();

        if($type == 'create') {
            $sth = $this->_db->prepare(
                "
                    INSERT INTO `html_records` SET
                    `name`  = ?,
                    `text`  = ?
                "
            );
            $sth->bindParam(1, $data['name']);
            $sth->bindParam(2, $data['text']);
            $sth->execute();
        }
        else {
            $sth = $this->_db->prepare(
                "
                    UPDATE `html_records` SET
                    `text`  = ?
                    WHERE `id` = ?
                "
            );
            $sth->bindParam(1, $data['text']);
            $sth->bindParam(2, $data['id']);
            $sth->execute();
        }
    }

    /**
     * @param $arr
     * @return array
     */
    public function create($arr)
    {
        return $this->create_update($arr);
    }

    /**
     * @param $arr
     * @param $id
     * @return array
     */
    public function update($arr, $id)
    {
        $obj            = $this->get($id);
        $arr['name']    = $obj['name'];
        $arr['id']      = $id;

        return $this->create_update($arr, 'update');
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $langs  = \system::get_languages();
        $tmp    = [];
        foreach($langs as $el) {
            $tmp[] = $el['key'];
        }
        $langs  = $tmp;

        $obj    = $this->get($id);
        $name   = $obj['name'];
        $key    = "html_record_{$name}_alias";

        foreach($langs as $el) {
            $this->_db->query(
                "
                    DELETE FROM `{$el}`
                    WHERE `key` = '{$key}'
                "
            );
        }

        $sth =  $this->_db->prepare(
            "
                DELETE FROM `html_records`
                WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();
    }

    /**
     * @param $v
     * @return bool
     */
    public static function valid_unique($v)
    {
        $model  = \html::get_admin_model('records');

        $obj    = $model->get($v);

        if($obj) {
            $lang   = \html::read_lang('records_page');

            return $lang['record_exists'];
        }
        else {
            return false;
        }
    }
}