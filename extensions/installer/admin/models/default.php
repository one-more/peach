<?php
/**
 * Class defaultmodel
 *
 * @author Nikolaev D.
 */
class defaultmodel extends superModel {

    /**
     * @return array
     */
    public function get_daemons()
    {
        try{
            $sth = $this->_db->query(
                'select * from `extensions` right join `daemons` on
                `daemons`.extension = `extensions`.id'
            );

            return $sth->fetchAll();
        }
        catch(PDOException $e) {
            error::log($e->getMessage());

            error::show_error('an exception occurred');
        }
    }
}