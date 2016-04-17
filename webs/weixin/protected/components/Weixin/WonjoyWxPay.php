<?php

define('WXPAY_BASE_PATH', dirname(__FILE__).'/wxpay');

// 加载所必须的文件
require_once WXPAY_BASE_PATH.'/lib/WxPay.Data.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Exception.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Notify.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Config.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Api.php';
require_once WXPAY_BASE_PATH.'/WonjoyNotifyHandler.php';

class WonjoyWxPay extends CComponent {

    /**
     * @var WxAuth
     */
    protected $wxAuth;

    protected $expiredSeconds = 7000;

    public function init() {
        $config = appConfig();
        $this->wxAuth = Yii::app()->wxAuth;
    }

    public function createUnifiedOrder($order, $product) {
        $openId = $this->wxAuth->getOpenID();

        \Sucel\Common\Includes\Logger::log()->info('openid: '. $openId);

        $input = new WxPayUnifiedOrder();
        $input->SetBody($product['name']);
        $input->SetOut_trade_no($order['order_id']);
        $input->setTotal_fee($order['price_total']);
        $input->SetTime_start(date('YmdHis', NOW));
        $input->SetTime_expire(date('YmdHis', NOW + $this->expiredSeconds));
        $input->SetNotify_url($this->wxAuth->getFullURL('/weixin/notify'));
        $input->SetTrade_type('JSAPI');
        $input->SetOpenid($openId);

        $unifedOrder = WxPayApi::unifiedOrder($input);

        \Sucel\Common\Includes\Logger::log()->info('create unified order: '.json_encode($unifedOrder));

        if ($unifedOrder && getParam($unifedOrder, 'return_code') != 'SUCCESS') {
            throw new \CHttpException(500, $unifedOrder['return_msg']);
        }

        return $unifedOrder;
    }

    /**
     * 生成 JSPAY 所需参数
     * @param $order
     * @param $product
     */
    public function generateJSPaymentParams($UnifiedOrderResult) {
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        return $jsapi->GetValues();
    }

    public function handleNotify() {
        $notifyHandler = new WonjoyNotifyHandler();
        $notifyHandler->Handle(false);
    }
}