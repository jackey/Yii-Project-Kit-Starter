<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 8/12/15
 * Time: 2:26 PM
 */

class WeixinController extends WxController {

    /**
     * @var WonjoyWxPay
     */
    protected $wxPay;

    public function init() {
        parent::init();
        $this->wxPay = Yii::app()->wxPay;
    }

    public function actionCallback() {
        $code = $this->getParam('code');
        // 出现异常？
        if (!$code) {
            $this->redirect($this->createUrl(array('index', 'index')));
            Yii::app()->exit();
        }
        else {
            $accessToken = $this->wxAuth->getAuth2AccessToken($code);
            if ($accessToken) {
                $callback = $this->getWxAuthCallback();
                if (!$callback) $callback = '/index/index';
                $this->redirect($this->createUrl($callback));
                Yii::app()->exit();
            }
        }
    }

    public function actionNotify() {
        $this->wxPay->handleNotify();
    }
}