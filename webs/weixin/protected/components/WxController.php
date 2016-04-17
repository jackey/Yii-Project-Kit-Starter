<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 10:29 AM
 */

class WxController extends CController {

    /**
     * @var WxAuth;
     */
    protected $wxAuth;

    public function init() {
        $this->wxAuth = Yii::app()->wxAuth;

        // 设置API 版本
        \Sucel\Common\Client\CClient::$apiURL = Yii::app()->params['api_url'];
        \Sucel\Common\Client\CClient::$version = Yii::app()->params['api_version'];

        return parent::init();
    }

    public function setWxAuthCallback($callback) {
        Yii::app()->session['wx_auth_callback'] = $callback;
    }

    public function getWxAuthCallback() {
        return Yii::app()->session['wx_auth_callback'];
    }

    public function getParam($name, $default = 0) {
        return Yii::app()->getRequest()->getParam($name, $default);
    }

    public function wxUserInfo() {
        if ($this->wxAuth->isAuthed()) {
            $wxAPI = $this->wxAuth->getWxAPIInstance();
            $userInfo = $wxAPI->getUserBasicInfo();
            if (getParam($userInfo, 'errcode')) return false;

            return $userInfo;
        }
        return false;
    }

    public function setUserInfo($user) {
        Yii::app()->session['user_info'] = $user;
    }

    public function userInfo() {
        return Yii::app()->session['user_info'];
    }

    public function forceWxLogin($callback = '') {
        if ($callback) $this->setWxAuthCallback($callback);
        $url = $this->wxAuth->getAuthCodeURL();
        header('Location: '. $url);
        Yii::app()->exit();
    }

    public function isPost() {
        return Yii::app()->request->isPostRequest;
    }

    public function isAjax() {
        return Yii::app()->request->isAjaxRequest;
    }

    public function loadProductInfo() {
        $productClient = new \Sucel\Common\Client\ProductClient();
        $productInfo = $productClient->info(Yii::app()->params['product_id']);
        return $productInfo['data'];
    }

    private function renderJSON($data, $message = "成功", $code = 200) {
        $data = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        $this->outputJSON($data);
    }

    public function outputJSON($data) {
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($data);
        die();
    }

    public function renderSuccessJSON($data) {
        $this->renderJSON($data);
    }

    public function renderErrorJSON($message, $code = 500) {
        $this->renderJSON(null, $message, $code);
    }

}