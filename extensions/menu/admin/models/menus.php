<?php
namespace menu_admin;

/**
 * Class menuscontroller
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class menusmodel extends \superModel {

    /**
     * @return array
     */
    public function get_menus()
    {
        $all = $this->get_all('menus');

        $lang = \system::get_current_lang();

        foreach($all as &$el) {
            $name   = $el['name'];
            $key    = "menu_{$name}_alias";
            $sth = $this->_db->query(
                "
                    SELECT `value` FROM `{$lang}`
                    WHERE `key` = '{$key}'
                "
            );
            $sth = $sth->fetch();
            $el['alias'] = $sth['value'];
        }

        return $all;
    }

    /**
     * @param $arr
     * @param string $type
     * @return array
     */
    public function create_update($arr, $type = 'create')
    {
        $default = [
          'name'    => '',
          'alias'   => ''
        ];

        if($type == 'create') {
            $checks = [
                'name'  => ['not_empty', 'unique_name'],
                'alias' => 'not_empty'
            ];
        }
        else {
            $checks = [
                'alias' => 'not_empty'
            ];
        }

        $data = array_merge($default, $arr);

        $errors = static::check($data, $checks);

        if($errors) {
            return $errors;
        }

        $data['name'] = preg_replace('/\s/', '_', $data['name']);

        $lang   = \system::get_current_lang();
        $name   = $data['name'];
        $key    = "menu_{$name}_alias";

        $sth = $this->_db->prepare(
            "
                INSERT INTO `{$lang}` SET
                 `key`    = '$key',
                 `value`  = :alias
                 ON DUPLICATE KEY UPDATE
                 `value` = :alias
            "
        );
        $sth->bindParam(':alias', $data['alias']);
        $sth->execute();

        if($type == 'create') {

            $sth = $this->_db->prepare(
                "
                    INSERT INTO `menus` SET
                    `name` = ?
                "
            );
            $sth->bindParam(1, $data['name']);
            $sth->execute();
        }
    }

    /**
     * @param $v
     * @return bool
     */
    public static function valid_unique_name($v)
    {
        $model = \menu::get_admin_model('menus');

        $record = $model->get($v);

        if($record) {
            $lang = \menu::read_lang('menus_page');

            return $lang['menu_exists'];
        }
        else {
            return false;
        }
    }

    /**
     * @param $param
     * @return mixed
     */
    public function get($param)
    {
        $sth = $this->_db->prepare(
            "
                SELECT * FROM `menus`
                WHERE `id` = :param OR `name` = :param
            "
        );
        $sth->bindParam(':param', $param);
        $sth->execute();
        $obj = $sth->fetch();

        if($obj) {
            $lang   = \system::get_current_lang();
            $name   = $obj['name'];
            $key    = "menu_{$name}_alias";

            $sth = $this->_db->query(
                "
                    SELECT `value` FROM `{$lang}`
                    WHERE `key` = '{$key}'
                "
            );
            $sth = $sth->fetch();
            $obj['alias'] = $sth['value'];
        }

        return $obj;
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
        $obj = $this->get($id);
        $arr['name'] = $obj['name'];

        return $this->create_update($arr, 'update');
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $obj    = $this->get($id);
        $name   = $obj['name'];
        $key    = "menu_{$name}_alias";

        $langs = \system::get_languages();
        $tmp = [];
        foreach($langs as $el) {
            $tmp[] = $el['key'];
        }
        $langs = $tmp;

        foreach($langs as $el) {
            $this->_db->query(
                "
                    DELETE FROM `{$el}`
                    WHERE `key` = '{$key}'
                "
            );
        }

        $sth = $this->_db->prepare(
            "
                DELETE FROM `menus`
                WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();
    }
}