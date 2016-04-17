<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 11/11/15
 * Time: 6:48 PM
 */

namespace Sucel\Service\Dao;

use Sucel\Common\Includes\Database\CDao;

class ProductBaseDao extends CDao {

    const STATUS_ONLINE = 1; // 活动发布在线
    const STATUS_OFFLINE = 2; // 活动已下线

    public function tableName() {
        return 't_product_base';
    }

    public function primaryKey() {
        return 'Fid';
    }

    /**
     * @param string $class
     * @return ProductBaseDao
     */
    public static function model($class=__CLASS__) {
        return parent::model($class);
    }

}