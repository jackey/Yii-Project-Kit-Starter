<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 9:00 PM
 */

namespace Sucel\Service\Model;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Common\Includes\QrCode;
use Sucel\Service\Dao\OrderDao;
use Sucel\Service\Dao\ProductBaseDao;
use Sucel\Service\Dao\ProductSetDao;
use Sucel\Service\Dao\TicketDao;
use Sucel\Service\Dao\UserDao;

class TicketModel {

    public static function generateTicketForOrder($orderId) {
        $orderDao = OrderDao::model()->findBypk($orderId);
        if (!$orderDao) throw new \CHttpException(ErrorDesc::ORDER_NOT_EXIST_, ErrorDesc::mean(ErrorDesc::ORDER_NOT_EXIST));

        if ($orderDao->Fstatus != OrderDao::STATUS_PAIED) throw new \CHttpException(ErrorDesc::ORDER_NOT_PAY, ErrorDesc::mean(ErrorDesc::ORDER_NOT_PAY));

        $count = $orderDao->Fcount;
        $productId = $orderDao->Fproduct;
        $productSet = $orderDao->Fproduct_set;

        $query = new \CDbCriteria();
        $query->addCondition('Forder=:order');
        $query->params[':order'] = $orderId;
        $exist = TicketDao::model()->exists($query);
        if ($exist) return true;

        $productDao = ProductBaseDao::model()->findByPk($productId);

        for ($i = 0; $i < $count; $i++) {
            $ticketDao = new TicketDao();
            $ticketDao->Forder = $orderDao->Fid;
            $ticketDao->Fstatus = TicketDao::STATUS_AVAILABLE;
            $ticketDao->Fcreated = NOW;
            $ticketDao->Fname = $productDao->Fname;
            $ticketDao->Fdesc = $productDao->Fdesc;
            $ticketDao->Fqrcode_uri = ''; // TODO:: 生成二维码
            $ticketDao->Fproduct = $productDao->Fid;
            $ticketDao->Fproduct_set = $productSet;
            $ticketDao->Fuid = $orderDao->Fuid;

            $ret = $ticketDao->save();
            // 生成二维码
            if ($ret) {
                $ticketID = $ticketDao->getPrimaryKey();
                $uri = QrCode::generateQRCodeAndUploadToFTP(json_encode(
                    array('ticket_id' => $ticketID)
                ));
                $ticketDao->Fqrcode_uri = $uri;
                $ticketDao->save();
            }
        }

        return true;
    }

    public static function appScanAndReturnInformation($ticketId) {
        $ticketDao = TicketDao::model()->findByPk($ticketId);
        if(!$ticketDao) throw new \CHttpException(ErrorDesc::TICKET_NOT_EXIST, ErrorDesc::mean(ErrorDesc::TICKET_NOT_EXIST));

        if ($ticketDao->Fstatus == TicketDao::STATUS_SCANED) {
            throw new \CHttpException(ErrorDesc::TICKET_SCANED, ErrorDesc::mean(ErrorDesc::TICKET_SCANED));
        }

        $productDao = ProductBaseDao::model()->findByPk($ticketDao->Fproduct);
        $productSetDao = ProductSetDao::model()->findByPk($ticketDao->Fproduct_set);

        $ticketDao->Fstatus = TicketDao::STATUS_SCANED;
        $ticketDao->Fscan_time = NOW;
        $ticketDao->save();

        return array(
            'name' => $productDao->Fname,
            'desc' => $productDao->Fdesc,
            'price' => $productSetDao->Fprice,
            'date' => $productSetDao->Fdate
        );
    }

    public static function loadUserTickets($uid, $offset = 0, $limit = 10) {
        $query = new \CDbCriteria();
        $query->addCondition('Fuid=:uid');
        $query->params[':uid'] = $uid;
        $query->limit = $limit;
        $query->offset = $offset;

        $ticketDaos = TicketDao::model()->findAll($query);

        return $ticketDaos;
    }

    public static function loadUserTicketsByOpenID($openID, $offset = 0, $limit = 10) {
        $query = new \CDbCriteria();
        $query->addCondition('Fopenid=:openid');
        $query->params[':openid'] = $openID;

        $userDao = UserDao::model()->find($query);
        if (!$userDao) {
            throw new \CHttpException(ErrorDesc::USER_NOT_EXIST, ErrorDesc::mean(ErrorDesc::USER_NOT_EXIST));
        }

        return self::loadUserTickets($userDao->Fid, $offset, $limit);
    }
}