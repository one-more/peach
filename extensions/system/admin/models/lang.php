<?php
namespace system_admin;

/**
 * Class langmodel
 * @package system_admin
 * @author Nikolaev D.
 */
class langmodel extends \superModel {

    public function add($alias, $key)
    {
        try{

            $this->get_reference();

            $data = ['alias'=>$alias, 'key'=>$key];

            static::$reference = array_merge(static::$reference, \system::read_lang('reference'));

            $error = static::check($data, [
                'key'   => ['not_empty', 'unique_key', 'valid_key'],
                'alias' => 'not_empty'
            ]);

            if(!$error) {
                $sth = $this->_db->prepare("insert into languages SET
                `alias` = ?,
                `key`   = ?");
                $sth->bindParam(1, $alias, \PDO::PARAM_STR);
                $sth->bindParam(2, $key, \PDO::PARAM_STR);

                $sth->execute();

                $this->_db->query("
                     CREATE TABLE IF NOT EXISTS `{$key}`(
                    `key` varchar(255) not null primary key unique ,
                    `value` text
                )");
            }

            return ['error'=>$error];
        }
        catch(\PDOException $e) {
            \error::log($e->getMessage());
            \error::show_error();
        }
    }

    /**
     * @param $v
     * @return bool
     */
    public static function valid_valid_key($v)
    {
        if(preg_match('/[a-z]{2}-[A-Z]{2}/', $v, $total)) {
            return false;
        }
        else {
            return static::$reference['not_valid_key'];
        }
    }

    /**
     * @param $v
     * @return bool
     */
    public static function valid_unique_key($v)
    {
        $arr = \system::get_languages();

        $langs = [];

        foreach($arr as $el) {
            $langs[$el['key']] = 1;
        }

        if(array_key_exists($v, $langs)) {
            return static::$reference['key_exists'];
        }
        else {
            return false;
        }
    }
}