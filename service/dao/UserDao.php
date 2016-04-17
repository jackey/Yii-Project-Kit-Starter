<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 11/11/15
 * Time: 6:48 PM
 */

namespace Sucel\Service\Dao;

use Sucel\Common\Includes\Database\CDao;

class UserDao extends CDao {

    public function tableName() {
        return 't_user';
    }

    public function primaryKey() {
        return 'Fid';
    }

    /**
     * @param string $class
     * @return UserDao
     */
    public static function model($class=__CLASS__) {
        return parent::model($class);
    }

}