<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 9:12 PM
 */
namespace Sucel\Service\Api\Ticket;

use Sucel\Service\Api\BaseApi;
use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Dao\ProductBaseDao;
use Sucel\Service\Dao\ProductSetDao;
use Sucel\Service\Model\TicketModel;

class Mine extends BaseApi {

    public function rules() {
        return array(
            'uid' => array(),
            'openid' => array(),
            'limit' => array(
                'default' => 10
            ),
            'offset' => array(
                'default' => 0
            )
        );
    }

    public function run() {
        $uid = $this->uid;
        $openid = $this->openid;
        if (!$uid && !$openid) {
            throw new \CHttpException(ErrorDesc::USER_OR_OEPNED_REQUIRED, ErrorDesc::mean(ErrorDesc::USER_OR_OEPNED_REQUIRED));
        }

        if ($openid) {
            $ticketDaos = TicketModel::loadUserTicketsByOpenID($openid,$this->offset, $this->limit);
        }
        else if ($uid) {
            $ticketDaos = TicketModel::loadUserTickets($uid ,$this->offset, $this->limit);
        }

        $pids = array();
        $psids = array();
        foreach ($ticketDaos as $ticketDao) {
            $pids[] = $ticketDao->Fproduct;
            $psids[] = $ticketDao->Fproduct_set;
        }

        // 加载产品
        $query = new \CDbCriteria();
        $query->addInCondition('Fid', $pids);
        $tmpProductDaos = ProductBaseDao::model()->findAll($query);
        $productDaos = array();
        foreach ($tmpProductDaos as $productDao) {
            $productDaos[$productDao->Fid] = $productDao;
        }

        // 加载产品系列
        $query = new \CDbCriteria();
        $query->addInCondition('Fid', $psids);
        $tmpProductSetDaos = ProductSetDao::model()->findAll($query);
        $productSetDaos = array();
        foreach ($tmpProductSetDaos as $productSetDao) {
            $productSetDaos[$productSetDao->Fid] = $productSetDao;
        }

        // 组装数据
        $tickets = array();

        foreach ($ticketDaos as $ticketDao) {
            $productDao = getParam($productDaos, $ticketDao->Fproduct);
            $productBaseDao = getParam($productDaos, $ticketDao->Fproduct_set);

            $tickets[] = array(
                'qrcode' => uploadImageURL($ticketDao->Fqrcode_uri),
                'uid' => $ticketDao->Fuid,
                'product' => array(
                    'name' => $productDao->Fname,
                    'desc' => $productDao->Fdesc,
                    'address' => $productDao->Faddress,
                ),
                'product_set' => array(
                    'price' => $productSetDao->Fprice,
                    'date' => $productSetDao->Fdate
                )
            );
        }

        return $tickets;

    }
}