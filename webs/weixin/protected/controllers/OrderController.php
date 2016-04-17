<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 2:09 PM
 */

use Sucel\Common\Client\UserClient;
use \Sucel\Common\Client\OrderClient;

class OrderController extends WxController {

    /**
     * @var WonjoyWxPay
     */
    protected $wxPay;

    public function init() {
        parent::init();
        $this->wxPay = Yii::app()->wxPay;
    }

    public function actionSubmit() {
        $product = $this->loadProductInfo();
        if ($this->isPost()) {
            $date = $this->getParam('date');
            $phone = $this->getParam('phone');
            $realname = $this->getParam('realname');
            $count = $this->getParam('count');

            // 电话号码检测
            if (!$phone || !Validator::phone($phone)) {
                $this->renderErrorJSON('电话号码格式不正确');
            }

            // 日期检测
            $validDate = false;
            $productSets = getParam($product, 'set');
            foreach ($productSets as $productSet) {
                if ($productSet['psid'] == $date) {
                    $validDate = true;
                    break;
                }
            }
            if (!$validDate) $this->renderErrorJSON('所选日期不在售票范围内');

            // 购票数量限制
            if ($count < 1) $this->renderErrorJSON('至少需要购买一张票');

            // 自动注册
            $userInfo = $this->userInfo();
            if (!$userInfo) {
                $userApi = new UserClient();
                $wxUser = $this->wxUserInfo();
                $userInfo = $userApi->register($phone, getParam($wxUser, 'openid'), getParam($wxUser, 'headimgurl'), getParam($wxUser, 'nickname'), $realname);

                if ($userInfo['code'] != 200) {
                    throw new \CHttpException($userInfo['code'], $userInfo['message']);
                }

                $this->setUserInfo($userInfo['data']);
                // 重新获取
                $userInfo = $this->userInfo();
            }

            //创建订单
            $orderApi = new OrderClient();
            $orderInfo = $orderApi->create($product['pid'], $count, $productSet['psid'], $userInfo['uid'], $phone);

            if ($orderInfo['code'] != 200) {
                $this->renderErrorJSON($orderInfo['message'], $orderInfo['code']);
            }

            $unifiedOrder = $this->wxPay->createUnifiedOrder($orderInfo['data'], $product);
            $jsParams = $this->wxPay->generateJSPaymentParams($unifiedOrder);

            \Sucel\Common\Includes\Logger::log()->debug(json_encode($jsParams));

            if ($jsParams) {
                $this->renderSuccessJSON($jsParams);
            }
        }
    }

    public function actionOrder() {
        $wxUserInfo = $this->wxUserInfo();
        if (!$wxUserInfo) {
            $this->forceWxLogin('/order/order');
        }
        $product = $this->loadProductInfo();

        $this->render('order', compact('product'));
    }

    public function actionTicket() {
        $userInfo = $this->userInfo();
        $ticketApi  = new \Sucel\Common\Client\TicketClient;
        if (!$userInfo) {
            $wxUserInfo = $this->wxUserInfo();
            if (!$wxUserInfo) {
                $this->forceWxLogin('/order/ticket');
            }
            $tickets = $ticketApi->getMyTicketsWithOpenID($this->wxAuth->getOpenID());
        }
        else {
            $userId = getParam($userInfo, 'uid');
            $tickets = $ticketApi->getMyTickets($userId);
        }

        $this->render('ticket', array('tickets' => $tickets['data']));
    }
}