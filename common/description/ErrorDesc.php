<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 7/12/15
 * Time: 6:28 PM
 */
namespace Sucel\Common\Description;

class ErrorDesc extends ADesc {

    const ORDER_USER_NAME_REQUIRED = 100;
    const ORDER_PHONE_REQUIRED = 101;
    const CODE_PHONE_REQUIRED = 102;
    const CODE_REQUIRED = 103;
    const CODE_WRONG = 104;
    const CODE_UNKNOWN_ERROR = 105;
    const ORDER_TICKET_COUNT_REQUIRED = 106;
    const IS_NUMBER = 107;
    const THIRD_ID_REQUIRED = 108;
    const SIGN_REQUIRED = 109;
    const SIGN_ERROR = 110;
    const ORDER_PRODUCT_REQUIRED = 111;
    const ORDER_DATE_REQUIRED = 112;
    const ORDER_STOCK_LIMITED = 113;
    const USER_PHONE_REQUIRED = 114;
    const USER_PHONE_REGISTERED = 115;
    const ORDER_USER_REQUIRED = 116;
    const PRODUCT_NOT_EXIST = 117;
    const ORDER_NOT_EXIST = 118;
    const USER_NOT_EXIST = 119;
    const ORDER_NOT_PAY = 201;
    const TICKET_NOT_EXIST = 202;
    const TICKET_SCANED = 203;
    const USER_OR_OEPNED_REQUIRED = 204;

    public function mapper() {
        return array(
            self::ORDER_PHONE_REQUIRED => '用户电话号码必须输入',
            self::ORDER_USER_NAME_REQUIRED => '用户姓名必须输入',
            self::CODE_PHONE_REQUIRED => '用户电话号码必须输入',
            self::CODE_REQUIRED  => '验证码必须输入',
            self::CODE_WRONG => '验证码不正确',
            self::CODE_UNKNOWN_ERROR => '验证码不正确或失效',
            self::ORDER_TICKET_COUNT_REQUIRED => '请输入购买数量',
            self::IS_NUMBER => '请输入数字',
            self::THIRD_ID_REQUIRED => '第三方用户ID必须传入',
            self::SIGN_REQUIRED => '签名验证参数不正确',
            self::SIGN_ERROR => '签名不匹配',
            self::ORDER_PRODUCT_REQUIRED => '必须选择产品',
            self::ORDER_DATE_REQUIRED => '请选择订单日期',
            self::ORDER_STOCK_LIMITED => '库存限制',
            self::USER_PHONE_REQUIRED => '用户手机号码必须输入',
            self::USER_PHONE_REGISTERED => '用户手机已注册',
            self::ORDER_USER_REQUIRED => '下订单情况下 用户必须输入',
            self::PRODUCT_NOT_EXIST => '产品不存在',
            self::ORDER_NOT_EXIST => '订单不存在',
            self::USER_NOT_EXIST => '用户不存在',
            self::ORDER_NOT_PAY => '订单未支付',
            self::TICKET_NOT_EXIST => '活动票不存在',
            self::TICKET_SCANED => '票已经扫描过',
            self::USER_OR_OEPNED_REQUIRED => '用户ID或OPENID必须传入任意一个',
        );
    }
}