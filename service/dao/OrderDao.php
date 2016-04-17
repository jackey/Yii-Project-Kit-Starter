<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 11/11/15
 * Time: 6:48 PM
 */

namespace Sucel\Service\Dao;

use Sucel\Common\Includes\Database\CDao;

class OrderDao extends CDao {

    const FROM_WEIXIN = 1;
    const FROM_APP = 2;
    const FROM_THIRD = 3;

    const PAY_TYPE_WECHAT = 1;
    const PAY_TYPE_ALIPAY = 2;

    const PAY_CHANNEL_WEB = 1;
    const PAY_CHANNEL_APP = 2;

    const STATUS_WAIT_TO_PAY = 1; // 等待支付
    const STATUS_PAIED = 2; // 已支付
    const STATUS_EXPIRED = 3; // 过期

    public function tableName() {
        return 't_order';
    }

    public function primaryKey() {
        return 'Fid';
    }

    /**
     * @param string $class
     * @return OrderDao
     */
    public static function model($class=__CLASS__) {
        return parent::model($class);
    }

}