<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 7/12/15
 * Time: 6:15 PM
 */

namespace Sucel\Service\Api\Order;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Api\BaseApi;
use Sucel\Service\Dao\ProductBaseDao;
use Sucel\Service\Dao\ProductSetDao;
use Sucel\Service\Model\OrderModel;

class Create extends BaseApi {

    public function rules() {
        return array(
            'phone' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::ORDER_PHONE_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::ORDER_PHONE_REQUIRED)
                    )
                )
            ),
            'count' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::ORDER_TICKET_COUNT_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::ORDER_TICKET_COUNT_REQUIRED),
                    ),
                    'Number' => array()
                )
            ),
            'product' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::ORDER_PRODUCT_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::ORDER_PRODUCT_REQUIRED)
                    )
                )
            ),
            'psid' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::ORDER_DATE_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::ORDER_DATE_REQUIRED),
                    ),
                )
            ),
            'uid' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::ORDER_USER_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::ORDER_USER_REQUIRED),
                    )
                )
            )
        );
    }

    public function run() {
        $orderDao = OrderModel::createOrder($this->product, $this->psid, $this->count, $this->uid, $this->phone);

        $productBaseDao = ProductBaseDao::model()->findByPk($orderDao->Fproduct);
        $productSetDao = ProductSetDao::model()->findByPk($orderDao->Fproduct_set);

        return array(
            'order_id' => $orderDao->Fid,
            'price_total' => $orderDao->Fprice_total,
            'product' => array(
                'name' => $productBaseDao->Fname,
                'desc' => $productBaseDao->Fdesc,
                'pid' => $productBaseDao->Fid,
                'address' => $productBaseDao->Faddress,
                'created' => $productBaseDao->Fcreated,
                'date' => $productSetDao->Fdate,
                'price' => $productSetDao->Fprice
            )
        );
    }

}