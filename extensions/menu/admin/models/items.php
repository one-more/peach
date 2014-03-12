<?php
namespace menu_admin;

/**
 * Class itemsmodel
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class itemsmodel extends \superModel {

    /**
     * @param $id
     * @return array
     */
    public function get_items($id)
    {
        if($id) {
            $sth = $this->_db->prepare(
                '
                    SELECT * FROM `menu_items`
                    WHERE `menu` = ?
                '
            );
            $sth->bindParam(1, $id);
            $sth->execute();

            $arr = $sth->fetchAll();
        }
        else {
            $arr = $this->get_all('menu_items');
        }

        $lang = \system::get_current_lang();

        $model = \menu::get_admin_model('menus');
        $menus = $model->get_menus();

        foreach($arr as &$el) {
            $name = $el['name'];
            $key = "menu_item_{$name}_alias";

            $sth = $this->_db->query(
                "
                    SELECT `value` FROM `{$lang}`
                    WHERE `key` = '{$key}'
                "
            );
            $sth = $sth->fetch();

            $el['alias'] = $sth['value'];

            foreach($menus as $menu) {
                if($el['menu'] == $menu['id']) {
                    $el['menu_alias'] = $menu['alias'];
                }
            }
        }

        return $arr;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $sth = $this->_db->prepare(
            "
                SELECT * FROM `menu_items`
                WHERE `id`  = :id
                OR `name`   = :id
            "
        );
        $sth->bindParam(':id', $id);
        $sth->execute();
        $obj = $sth->fetch();

        if($obj) {
            $lang   = \system::get_current_lang();
            $name   = $obj['name'];
            $key    = "menu_item_{$name}_alias";

            $sth    = $this->_db->query(
                "
                SELECT `value` FROM `{$lang}`
                WHERE `key` = '{$key}'
            "
            );
            $sth            = $sth->fetch();
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
            'name'      => '',
            'alias'     => '',
            'menu'      => '',
            'url'       => '',
            'parent'    => ''
        ];

        $data = array_merge($default, $arr);

        $data['name'] = preg_replace('/\s/', '_', $data['name']);


        if($type == 'create') {
            $check = [
                'name'      => ['not_empty', 'unique_item'],
                'alias'     => 'not_empty',
                'menu'      => 'not_empty',
                'url'       => 'not_empty'
            ];
        }
        else {
            $check = [
                'alias' => 'not_empty',
                'menu'  => 'not_empty',
                'url'   => 'not_empty'
            ];
        }

        $errors = static::check(
            $data,
            $check
        );

        if($errors) {
            return $errors;
        }

        $lang   = \system::get_current_lang();
        $name   = $data['name'];
        $key    = "menu_item_{$name}_alias";

        $sth    = $this->_db->prepare(
            "
                INSERT INTO `{$lang}` SET
                `key`   = :name,
                `value` = :alias
                ON DUPLICATE KEY UPDATE
                `value` = :alias
            "
        );
        $sth->bindParam(':name', $key);
        $sth->bindParam(':alias', $data['alias']);
        $sth->execute();

        if($type == 'create') {
            $sth = $this->_db->prepare(
                "
                    INSERT INTO `menu_items` SET
                    `name`    = ?,
                    `url`     = ?,
                    `menu`    = ?,
                    `parent`  = ?
                "
            );
            $sth->bindParam(1, $data['name']);
            $sth->bindParam(2, $data['url']);
            $sth->bindParam(3, $data['menu']);
            $sth->bindParam(4, $data['parent']);
            $sth->execute();

            return $this->_db->lastInsertId();
        }
        else {
            $sth = $this->_db->prepare(
                "
                    UPDATE `menu_items` SET
                    `url`     = ?,
                    `menu`    = ?,
                    `parent`  = ?
                    WHERE `id` = ?
                "
            );
            $sth->bindParam(1, $data['url']);
            $sth->bindParam(2, $data['menu']);
            $sth->bindParam(3, $data['parent']);
            $sth->bindParam(4, $data['id']);
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
        $arr['id']      = $obj['id'];

        return $this->create_update($arr, 'update');
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $langs = \system::get_languages();
        $tmp = [];
        foreach($langs as $el) {
            $tmp[] = $el['key'];
        }
        $langs = $tmp;

        $obj = $this->get($id);
        $name = $obj['name'];
        $key = "menu_item_{$name}_alias";

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
                DELETE FROM `menu_items`
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
    public static function valid_unique_item($v) {
        $model = \menu::get_admin_model('items');

        $obj = $model->get($v);

        if($obj) {
            $lang = \menu::read_lang('items_page');

            return $lang['item_exists'];
        }
        else {
            return false;
        }
    }
}