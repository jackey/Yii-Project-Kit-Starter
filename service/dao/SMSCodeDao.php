<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 11/11/15
 * Time: 6:48 PM
 */

namespace Sucel\Service\Dao;

use Sucel\Common\Includes\Database\CDao;

class SMSCodeDao extends CDao {

    const TYPE_REGISTER = 1; // 注册验证码

    const TYPE_LOGIN = 2; // 登陆验证码

    const TYPE_VALID = 3 ; // 验证类型

    const STATUS_UNUSED = 0; // 验证码未使用
    const STATUS_USED = 1; // 验证码已使用

    public function tableName() {
        return 't_sms_code';
    }

    public function primaryKey() {
        return 'Fid';
    }

    public static function model($class=__CLASS__) {
        return parent::model($class);
    }

}