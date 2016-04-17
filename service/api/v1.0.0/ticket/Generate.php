<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 9:10 PM
 */
namespace Sucel\Service\Api\Ticket;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Api\BaseApi;
use Sucel\Service\Model\OrderModel;
use Sucel\Service\Model\TicketModel;

class Generate extends BaseApi {

    public function rules() {
        return array(
            'order' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::ORDER_NOT_EXIST,
                        'message' => ErrorDesc::mean(ErrorDesc::ORDER_NOT_EXIST),
                    )
                )
            )
        );
    }

    public function run() {
        OrderModel::isPaied($this->order);
        return TicketModel::generateTicketForOrder($this->order);
    }
}