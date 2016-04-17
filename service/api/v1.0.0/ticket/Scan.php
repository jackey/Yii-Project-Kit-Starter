<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 10:47 PM
 */
namespace Sucel\Service\Api\Ticket;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Api\BaseApi;
use Sucel\Service\Model\TicketModel;

class Scan extends BaseApi {

    public function rules() {
        return array(
            'ticket' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::TICKET_NOT_EXIST,
                        'message' => ErrorDesc::mean(ErrorDesc::TICKET_NOT_EXIST),
                    )
                )
            )
        );
    }

    public function run() {
        return TicketModel::appScanAndReturnInformation($this->ticket);
    }
}