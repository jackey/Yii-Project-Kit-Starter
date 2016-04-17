<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 8/12/15
 * Time: 9:51 AM
 */

namespace Sucel\Service\Model;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Common\Description\OrderDesc;
use Sucel\Service\Dao\OrderDao;
use Sucel\Service\Dao\ProductBaseDao;
use Sucel\Service\Dao\ProductSetDao;
use Sucel\Service\Dao\TicketDao;
use Sucel\Service\Dao\UserDao;

class OrderModel {

    public static function createOrder($productId, $psid ,$count, $userID, $phone) {

        // 检查用户是否存在
        $userDao = UserDao::model()->findByPk($userID);
        if (!$userDao) {
            throw new \CHttpException(ErrorDesc::ORDER_USER_REQUIRED, ErrorDesc::mean(ErrorDesc::ORDER_USER_REQUIRED));
        }

        //检查产品是否存在
        $productBaseDao = ProductBaseDao::model()->findByPk($productId);
        if (!$productBaseDao) {
            throw new \CHttpException(ErrorDesc::ORDER_PRODUCT_REQUIRED, ErrorDesc::mean(ErrorDesc::ORDER_PRODUCT_REQUIRED));
        }

        // 检查日期是否正确
        $productSetDao = ProductSetDao::model()->findByPk($psid);
        if (!$productSetDao) {
            throw new \CHttpException(ErrorDesc::ORDER_DATE_REQUIRED, ErrorDesc::mean(ErrorDesc::ORDER_DATE_REQUIRED));
        }

        // 检查库存
        $stock = ProductModel::countProductStock($productBaseDao->getPrimaryKey());
        // 库存不足
        if ($stock <= 0) {
            throw new \CHttpException(ErrorDesc::ORDER_STOCK_LIMITED, ErrorDesc::ORDER_STOCK_LIMITED);
        }

        $orderDao = new OrderDao();
        $priceTotal = $productSetDao['Fprice'] * $count;
        $orderDao->Fphone = $phone;
        $orderDao->Frealname = $userDao->Frealname;
        $orderDao->Fprice_total = $priceTotal;
        $orderDao->Ffrom = \Sucel\Service\Dao\OrderDao::FROM_WEIXIN;
        $orderDao->Fcreated = NOW;
        $orderDao->Fcount = $count;
        $orderDao->Fproduct = $productBaseDao->getPrimaryKey();
        $orderDao->Fproduct_set = $productSetDao->getPrimaryKey();
        $orderDao->Fstatus = OrderDao::STATUS_WAIT_TO_PAY;
        $orderDao->Fuid = $userDao->getPrimaryKey();
        $orderDao->save();

        return $orderDao;
    }

    public static function isPaied($orderId){
        $orderDao = OrderDao::model()->findByPk($orderId);

        if (!$orderDao) return false;

        $orderDao->Fprice_total_pay = $orderDao->Fprice_total;
        $orderDao->Fpay_channel = OrderDao::PAY_CHANNEL_WEB;
        $orderDao->Fpay_type = OrderDao::PAY_TYPE_WECHAT;
        $orderDao->Fpay_time = NOW;
        $orderDao->Fstatus = OrderDao::STATUS_PAIED;

        $orderDao->save();
    }




}