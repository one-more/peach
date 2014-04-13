<?php

namespace articles_admin;

/**
 * Class articlesmodel
 *
 * @package articles_admin
 *
 * @author Nikolaev D.
 */
class articlesmodel extends \superModel {

    /**
     * @param null $category
     * @param bool $published
     * @param bool $start
     * @param bool $offset
     * @return array
     */
    public function get_articles(
        $category = null,
        $published = false,
        $start = false,
        $offset = false
        )
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        if($start || $offset) {
            $where = $start ? ' `id` > ?' : '';
            $limit = $offset ? ' LIMIT ?' : '';

            if($category) {
                $where = $where ?
                    '`category` = ? and '.$where :
                    '`category` = ? ';
            }

            if($where) {
                $where = 'WHERE '.$where;
            }

            $sth = $this->_db->prepare(
                "
                    SELECT * FROM `{$lang}_articles`
                    {$where} {$limit}
                "
            );

            $param = 1;
            if($category) {
                $sth->bindParam($param, $category, \PDO::PARAM_STR);
                $param++;
            }
            if($start) {
                $sth->bindParam($param, $start, \PDO::PARAM_INT);
                $param++;
            }
            if($limit) {
                $sth->bindParam($param, $offset, \PDO::PARAM_INT);
            }

            $sth->execute();

            $arr = $sth->fetchAll();
        }
        elseif(!$category) {
            $arr    = $this->get_all("{$lang}_articles");
        }
        else {
            $sth = $this->_db->prepare(
                "
                    SELECT * FROM `{$lang}_articles`
                    WHERE `category` = ?
                "
            );
            $sth->bindParam(1, $category);
            $sth->execute();

            $arr = $sth->fetchAll();
        }

        if($published) {
            foreach($arr as $k=>$v) {
                if($v['published'] == 0) {
                    unset($arr[$k]);
                }
            }
        }

        $arr = $this->handle_articles($arr);

        return $arr;
    }

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
                SELECT * FROM `{$lang}_articles`
                WHERE `id` = :id
                OR `title` = :id
            "
        );
        $sth->bindParam(':id', $id);
        $sth->execute();

        $arr =  $sth->fetch();
        $oid = $arr['id'];

        if($oid) {
            $sth = $this->_db->query(
                "
                SELECT `name` FROM `{$lang}_article_tags` WHERE
                `id` in (
                 SELECT `tag` FROM `{$lang}_articles_tags`
                 WHERE `article` = {$oid}
                )
            "
            );
            $sth = $sth->fetchAll();
            $tags = [];

            foreach($sth as $el) {
                $tags[] = $el['name'];
            }

            $arr['tags'] = implode(',', $tags);
        }

        return $arr;
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $sth = $this->_db->prepare(
            "
                DELETE FROM `{$lang}_articles_tags`
                WHERE `article` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();

        $sth = $this->_db->prepare(
            "
                DELETE FROM `{$lang}_articles`
                WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);
        $sth->execute();
    }

    /**
     * @param $arr
     * @param string $type
     * @return array
     */
    public function create_update($arr, $type = 'create')
    {
        $default = [
            'title'     => '',
            'text'      => '',
            'category'  => '',
            'tags'      => ''
        ];

        $data = array_merge($default, $arr);

        foreach($data as &$el) {
            $el = trim($el);
        }

        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $data['author'] = \user::get_id();
        $data['published'] = empty($data['published'])? 0 : 1;

        if($type == 'create') {
            $error = static::check($data, [
               'title'      => ['not_empty', 'unique'],
                'text'      => 'not_empty',
                'category'  => 'not_empty',
                'tags'      => 'not_empty'
            ]);

            if($error) {
                return $error;
            }

            $sth = $this->_db->prepare(
                "
                    INSERT INTO `{$lang}_articles` SET
                    `title`     = ?,
                    `text`      = ?,
                    `category`  = ?,
                    `date`      = now(),
                    `author`    = ?,
                    `published` = ?
                "
            );
            $sth->bindParam(1, $data['title']);
            $sth->bindParam(2, $data['text']);
            $sth->bindParam(3, $data['category']);
            $sth->bindParam(4, $data['author']);
            $sth->bindParam(5, $data['published']);
            $sth->execute();

            $tags = explode(',', $data['tags']);

            $id = $this->_db->lastInsertId();

            foreach($tags as $el) {
                $sth = $this->_db->prepare(
                    "
                        INSERT INTO `{$lang}_articles_tags` SET
                        `article` = ?,
                        `tag`     =
                        (
                         SELECT `id` FROM `{$lang}_article_tags`
                         WHERE `name` = ?
                        )
                    "
                );
                $sth->bindParam(1, $id);
                $sth->bindParam(2, $el);
                $sth->execute();
            }
        }
        else {
            $obj = $this->get($data['id']);

            if($data['title'] != $obj['title']) {
                $title = ['not_empty', 'unique'];
            }
            else {
                $title = 'not_empty';
            }

            $error = static::check($data, [
               'title'      => $title,
                'text'      => 'not_empty',
                'category'  => 'not_empty',
                'tags'      => 'not_empty'
            ]);

            if($error) {
                return $error;
            }

            $sth = $this->_db->prepare(
                "
                    UPDATE `{$lang}_articles` SET
                    `title`     = ?,
                    `text`      = ?,
                    `category`  = ?,
                    `date`      = now(),
                    `author`    = ?,
                    `published` = ?
                    WHERE `id`  = ?
                "
            );
            $sth->bindParam(1, $data['title']);
            $sth->bindParam(2, $data['text']);
            $sth->bindParam(3, $data['category']);
            $sth->bindParam(4, $data['author']);
            $sth->bindParam(5, $data['published']);
            $sth->bindParam(6, $data['id']);
            $sth->execute();

            $tags       = explode(',', $data['tags']);
            $old_tags   = explode(',', $obj['tags']);

            if(count($old_tags) > count($tags)) {
                $diff = array_diff($old_tags, $tags);

                foreach($diff as $el) {
                    $this->_db->query(
                        "
                            DELETE FROM `{$lang}_articles_tags`
                            WHERE `tag` = (
                              SELECT `id` FROM `{$lang}_article_tags`
                              WHERE `name` = '{$el}'
                            )
                        "
                    );
                }
            }
            else {
                $diff = array_diff($tags, $old_tags);

                $id = $data['id'];

                foreach($diff as $el) {
                    $this->_db->query(
                        "
                            INSERT INTO `{$lang}_articles_tags` SET
                            `article` = {$id},
                            `tag`     = (
                              SELECT `id` FROM `{$lang}_article_tags`
                              WHERE `name` = '{$el}'
                            )
                        "
                    );
                }
            }
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
     * @return array
     */
    public function update($arr)
    {
        return $this->create_update($arr, 'update');
    }

    /**
     * @param $v
     * @return bool
     */
    public static function valid_unique($v)
    {
        $model = \articles::get_admin_model('articles');
        $params = \articles::read_lang('articles_page');

        $obj = $model->get($v);

        if($obj) {
            return $params['unique'];
        }
        else {
            return false;
        }
    }

    /**
     * @param $id
     */
    public function publish($id)
    {
        $obj = $this->get($id);

        $par = $obj['published']? 0 : 1;

        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $sth = $this->_db->prepare(
            "
                UPDATE `{$lang}_articles` SET
                `published` = {$par}
                WHERE `id` = ?
            "
        );
        $sth->bindParam(1, $id);

        $sth->execute();
    }

    /**
     * @param $tag
     * @param $start
     * @param $offset
     * @return array
     */
    public function get_articles_with_tag($tag, $start, $offset)
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $sth = $this->_db->prepare(
            "
                SELECT * FROM `{$lang}_articles`
                WHERE `id` in (
                    SELECT `article` FROM `{$lang}_articles_tags`
                    WHERE `tag` = (
                        SELECT `id` FROM `{$lang}_article_tags` WHERE `name` = ?
                    )
                )
                AND `published` = 1
                AND `id` > ? LIMIT ?
            "
        );
        $sth->bindParam(1, $tag, \PDO::PARAM_STR);
        $sth->bindParam(2, $start, \PDO::PARAM_INT);
        $sth->bindParam(3, $offset, \PDO::PARAM_INT);
        $sth->execute();


        $arr    = $sth->fetchAll();

        $arr    = $this->handle_articles($arr);

        return $arr;
    }

    /**
     * @param $word
     * @param $start
     * @param $offset
     * @return array|mixed
     */
    public function search($word, $start, $offset)
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $word   = strtolower($word);
        $search = $word;
        $word   = "%{$word}%";

        $sth = $this->_db->prepare(
            "
                SELECT * FROM `{$lang}_articles`
                WHERE (`title` LIKE :search
                OR `text`  LIKE :search)
                AND `published` = 1
                AND `id` > :start
                LIMIT :offset
            "
        );
        $sth->bindParam(':search', $word, \PDO::PARAM_STR);
        $sth->bindParam(':start', $start, \PDO::PARAM_INT);
        $sth->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $sth->execute();
        $arr = $sth->fetchAll();

        foreach($arr as &$el) {
            $el['text'] = preg_replace(
                "/({$search})/isU",
                '<span class="search-result">$1</span>',
                $el['text']
            );

            $el['title'] = preg_replace(
                "/({$search})/isU",
                '<span class="search-result">$1</span>',
                $el['title']
            );
        }

        $arr = $this->handle_articles($arr);

        return $arr;
    }

    /**
     * @param $arr
     * @return mixed
     */
    protected function handle_articles($arr)
    {
        $lang   = \system::get_current_lang();

        $lang   = preg_split('/-/', $lang)[0];

        $model  = \articles::get_admin_model('categories');

        foreach($arr as &$el) {
            $user = \user::get($el['author']);
            $user = is_array($user)? $user : json_decode($user, true);

            $el['author'] = $user['info']['full_name'] == '-' ?
                $user['user']['login'] : $user['info']['full_name'];

            $el['category'] = $model->get($el['category'])['name'];

            $id = $el['id'];
            $sth = $this->_db->query(
                "
                SELECT `name` FROM `{$lang}_article_tags` WHERE
                `id` in (
                 SELECT `tag` FROM `{$lang}_articles_tags`
                 WHERE `article` = {$id}
                )
            "
            );
            $sth = $sth->fetchAll();
            $tags = [];

            foreach($sth as $el1) {
                $tags[] = $el1['name'];
            }

            $el['tags'] = implode(',', $tags);
        }

        return $arr;
    }
}