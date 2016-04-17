<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 7/12/15
 * Time: 7:08 PM
 */

namespace Sucel\Service\Api\Sms;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Api\BaseApi;
use Sucel\Service\Model\SMSModel;

class ValidBuyticketCode extends BaseApi {

    public function rules() {
        return array(
            'phone' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::CODE_PHONE_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::CODE_PHONE_REQUIRED),
                    )
                )
            ),
            'code' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::CODE_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::CODE_REQUIRED),
                    )
                )
            )
        );
    }

    public function run() {
        return SMSModel::validCodeWhenBuyTicket($this->phone, $this->code);
    }
}