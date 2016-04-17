<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 7/12/15
 * Time: 6:34 PM
 */

namespace Sucel\Service\Api\Sms;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Api\BaseApi;
use Sucel\Service\Model\SMSModel;

class BuyticketCode extends BaseApi {

    public function rules() {
        return array(
            'phone' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::CODE_PHONE_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::CODE_PHONE_REQUIRED)
                    )
                )
            ),
            'realname' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::ORDER_USER_NAME_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::ORDER_USER_NAME_REQUIRED)
                    )
                ),
            ),
        );
    }

    public function run() {
        return SMSModel::sendValidCodeWhenBuyTicket($this->realname, $this->phone);
    }
}