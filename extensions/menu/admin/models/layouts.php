<?php
namespace menu_admin;

/**
 * Class layputsmodel
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class layoutsmodel extends \superModel {

    /**
     * @param $arr
     * @param string $type
     * @return array
     */
    public function create_update($arr, $type = 'create')
    {

        $lang = \system::get_current_lang();


        if($type == 'create') {

            if(!empty($arr['name'])) {
                $arr['name'] = preg_replace('/\s/', '_', $arr['name']);

                $sth = $this->_db->prepare(
                    "SELECT * FROM layout WHERE `name` = ?"
                );
                $sth->bindParam(1, $arr['name'], \PDO::PARAM_STR);
                $sth->execute();

                if($sth->fetch()) {
                    $lang = \menu::read_lang('layouts_page');

                    return $lang['layout_exists'];
                }
            }

            $default = [
                'name'          => '',
                'alias'         => '',
                'extension'     => '',
                'class_name'    => '',
                'controller'    => '',
                'position'      => '',
                'url'           => ''
            ];

            $data = array_merge($default, $arr);

            $errors = static::check(
                $data,
                [
                    'name'          => 'not_empty',
                    'alias'         => 'not_empty',
                    'extension'     => 'not_empty',
                    'class_name'    => 'not_empty',
                    'controller'    => 'not_empty',
                    'position'      => 'not_empty',
                    'url'           => 'not_empty'
                ]
            );

            if($errors) {
                return $errors;
            }

            $params = [];
            $keys = array_keys($default);
            $keys[] = 'add-url-input';
            foreach($arr as $k=>$v) {
                if(!in_array($k, $keys)) {
                    $params[$k] = $v;
                }
            }
            $params = json_encode($params);

            $alias_key = "layout_{$data['name']}_alias";

            /*-------------*/

            $sth = $this->_db->prepare(
                "INSERT INTO `{$lang}` SET
                    `key`   = ?,
                    `value` = ?
                "
            );
            $sth->bindParam(1, $alias_key, \PDO::PARAM_STR);
            $sth->bindParam(2, $data['alias'], \PDO::PARAM_STR);
            $sth->execute();

            $sth = $this->_db->prepare(
                "INSERT INTO layout SET
                    `name`          = ?,
                    `extension`     = ?,
                    `class`         = ?,
                    `controller`    = ?,
                    `position`      = ?
                "
            );
            $sth->bindParam(1, $data['name']);
            $sth->bindParam(2, $data['extension']);
            $sth->bindParam(3, $data['class_name']);
            $sth->bindParam(4, $data['controller']);
            $sth->bindParam(5, $data['position']);
            $sth->execute();

            $layout_id = $this->_db->lastInsertId();

            foreach($data['url'] as $el) {
                $sth = $this->_db->prepare(
                    "
                        INSERT INTO layout_url SET
                          `layout`  = ?,
                          `url`     = (select `id` from `url` where `url` = ?)
                    "
                );
                $sth->bindParam(1, $layout_id);
                $sth->bindParam(2, $el);
                $sth->execute();
            }

            $sth = $this->_db->prepare(
                "INSERT INTO layout_params SET
                    layout = ?,
                    params = ?
                "
            );
            $sth->bindParam(1, $layout_id);
            $sth->bindParam(2, $params);
            $sth->execute();
        }
        elseif($type == 'update') {

            $default = [
              'alias'       => '',
              'position'    => '',
              'url'         => ''
            ];

            $data = array_merge($default, $arr);

            $errors = static::check(
                $data,
                [
                    'alias'     => 'not_empty',
                    'position'  => 'not_empty',
                    'url'       => 'not_empty'
                ]
            );

            if($errors) {
                return $errors;
            }

            $alias_key = "layout_{$data['name']}_alias";

            /*----------------------*/

            $sth = $this->_db->prepare(
                "SELECT `url`.`url` FROM `layout_url`
                LEFT JOIN `url` ON `url`.`id` = `layout_url`.`url`
                WHERE `layout_url`.`layout` = ?"
            );
            $sth->bindParam(1, $data['id']);
            $sth->execute();
            $old_urls = $sth->fetchAll();
            $tmp = [];
            foreach($old_urls as $el) {
                $tmp[] = $el['url'];
            }
            $old_urls = $tmp;

            if(count($data['url']) > count($old_urls)) {
                $diff = array_diff($data['url'], $old_urls);

                foreach($diff as $el) {
                    $sth = $this->_db->prepare(
                        "
                            INSERT INTO layout_url SET
                              `layout`  = ?,
                              `url`     = (select `id` from `url` where `url` = ?)
                        "
                    );
                    $sth->bindParam(1, $data['id']);
                    $sth->bindParam(2, $el);
                    $sth->execute();
                }
            }
            else {
                $diff = array_diff($old_urls, $data['url']);

                foreach($diff as $el) {
                    $sth = $this->_db->prepare(
                        "DELETE FROM layout_url WHERE
                            `url` = (select `id` from `url` where `url` = ?)
                        "
                    );
                    $sth->bindParam(1, $el);
                    $sth->execute();
                }
            }

            $sth = $this->_db->prepare(
                "UPDATE `{$lang}` SET `value` = ? where `key` = '{$alias_key}'"
            );
            $sth->bindParam(1, $data['alias']);
            $sth->execute();

            $sth = $this->_db->prepare(
                "UPDATE `layout` SET `position` = ? where `id` = ?"
            );
            $sth->bindParam(1, $data['position']);
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
        $obj = $this->get($id);
        $arr['name'] = $obj['name'];
        $arr['id'] = $id;

        return $this->create_update($arr, 'update');
    }

    /**
     * @return array
     */
    public function get_layouts()
    {

        $lang = \system::get_current_lang();

        $arr = $this->get_all('layout');

        foreach($arr as &$el) {
            $name = $el['name'];
            $sth = $this->_db->query(
                "
                    SELECT `value` FROM `{$lang}`
                    WHERE `key` = 'layout_{$name}_alias'
                "
            );
            $sth = $sth->fetch();
            $el['alias'] = $sth['value'];
        }

        return $arr;
    }

    /**
     * @param $id
     */
    public function delete($id) {

        $sth = $this->_db->prepare(
            "DELETE FROM `layout_url` WHERE `layout` = ?"
        );
        $sth->bindParam(1, $id);
        $sth->execute();

        $sth = $this->_db->prepare(
            "DELETE FROM `layout_params` WHERE `layout` = ?"
        );
        $sth->bindParam(1, $id);
        $sth->execute();

        $sth = $this->_db->prepare(
            "
                SELECT `name` FROM `layout` WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();
        $sth = $sth->fetch();
        $name = $sth['name'];

        $langs = \system::get_languages();
        foreach($langs as $el) {

            $key = $el['key'];

            $this->_db->query(
                "
                    DELETE FROM `{$key}`
                    WHERE `key` = 'layout_{$name}_alias'
                "
            );
        }

        $sth = $this->_db->prepare(
            "
                DELETE FROM `layout` WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $lang = \system::get_current_lang();

        $sth = $this->_db->prepare(
            "
                SELECT * FROM `layout`
                WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();

        $arr = $sth->fetch();

        $name = $arr['name'];

        $sth = $this->_db->query(
            "
                SELECT `value` FROM `{$lang}`
                WHERE `key` = 'layout_{$name}_alias'
            "
        );
        $sth = $sth->fetch();
        $arr['alias'] = $sth['value'];


        $sth = $this->_db->prepare(
            "
                SELECT `url`.`url` FROM `layout_url`
                LEFT JOIN `url`
                ON `layout_url`.`url` = `url`.`id`
                WHERE `layout_url`.`layout` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();

        $arr['urls'] = $sth->fetchAll();

        return $arr;
    }

    public function get_page($url)
    {
        $links = \menu::get_urls();
        $needle = false;
        $tmp = [];
        foreach($links as $el) {
            $tmp[] = $el['url'];
        }
        $links = $tmp;

        if(strlen($url) > 1 && $url[strlen($url)-1] == '/') {
            $url[strlen($url)-1] = "";
        }

        if(array_search($url, $links) !== false) {
            $needle = $url;
        }
        else {
            $parts = explode('/', $url);
            for($i = count($parts); $i>2; $i--) {
                $parts[$i-1] = '*';

                if(array_search(implode('/', $parts), $links) !== false) {
                    $needle = $url;

                    break;
                }
            }
        }

        if($needle !== false) {
            $sth = $this->_db->prepare(
                "
                    SELECT `layout`.`id`, `class`, `controller`, `position`
                    FROM `layout_url`
                    LEFT JOIN `layout`
                    ON `layout_url`.`layout` = `layout`.`id`
                    WHERE `layout_url`.`url` =
                    (SELECT `id` FROM `url` WHERE `url` = ?)
                "
            );
            $sth->bindParam(1, $needle);
            $sth->execute();

            return $sth->fetchAll();
        }
        else {
            return [];
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_layout_params($id)
    {
        $sth = $this->_db->prepare(
            "
                SELECT * FROM `layout_params`
                WHERE `layout` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();

        $sth = $sth->fetch();

        return json_decode($sth['params'], true);
    }
}